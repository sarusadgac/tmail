<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Util {

    public static function checkDatabaseConnection() {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /** Check for Update - License control removed - v8.0.0 */
    public static function checkForAppUpdate() {
        // Auto update service disabled - License system removed for v8.0.0
        try {
            return [
                'available' => false,
                'disabled' => true,
                'error' => false,
                'message' => 'Auto update service disabled - License system removed for v8.0.0. Manual updates only.'
            ];
        } catch (Exception $e) {
            Log::alert($e->getMessage());
            return [
                'error' => true
            ];
        }
    }

    public static function getTranslatedPage($page_id) {
        $page = Page::where('id', $page_id)->where('is_published', true)->first();
        if (!$page) {
            return false;
        }

        $currentLocale = app()->getLocale();
        $defaultLocale = config('app.settings.language');

        // If we're not on the default language, try to get the translation
        if ($currentLocale !== $defaultLocale) {
            $translation = $page->translation($currentLocale);
            if ($translation) {
                // Create a temporary object with translated content
                $translatedPage = clone $page;
                $translatedPage->title = $translation->title;
                $translatedPage->content = $translation->content;
                $translatedPage->meta = $translation->meta;
                $translatedPage->header = $translation->header;
                return $translatedPage;
            }
        }

        return $page;
    }

    public static function deletePageMenuLink($page_id) {
        $url = config('app.url');
        $page = Page::find($page_id);
        $url = $url . '/' . $page->slug;
        Menu::where('link', $url)->delete();
    }

    public static function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }

    public static function translateModelAttribute($fields, $table, $name, $value) {
        $item = null;
        if (in_array($table, ['menus'])) {
            if ($fields->translations) {
                $translations = unserialize($fields->translations);
                if (isset($translations[app()->getLocale()]) && $translations[app()->getLocale()]) {
                    return $translations[app()->getLocale()];
                }
            }
        }
        if ($item) {
            return $item->{$name};
        }
        return $value;
    }

    public static function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
                        self::rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                    else
                        unlink($dir . DIRECTORY_SEPARATOR . $object);
                }
            }
            rmdir($dir);
        }
    }

    public static function localizeUrl($url) {
        if (config('app.settings.language_in_url', false) === true) {
            $urls = [config('app.url')];
            $languages = Setting::pick('languages');
            foreach ($languages as $code => $language) {
                if ($language['is_active'] === false) {
                    continue;
                }
                array_push($urls, config('app.url') . '/' . $code);
            }
            $locale = app()->getLocale();
            $replacement = config('app.url') . '/' . $locale;
            foreach ($urls as $u) {
                $url = str_replace($u, $replacement, $url);
            }
        }
        return $url;
    }

    public static function localizeRoute($routeName, $parameters = [], $absolute = true) {
        $url = route($routeName, $parameters, $absolute);
        return self::localizeUrl($url);
    }

    public static function getBlogs($category_id = null) {
        $currentLocale = app()->getLocale();
        $defaultLocale = config('app.settings.language');

        // Get all published posts
        $posts = Post::with('categories', 'translations')
            ->where('is_published', true);

        if ($category_id) {
            $posts->whereHas('categories', function ($query) use ($category_id) {
                $query->where('categories.id', $category_id);
            });
        }

        $posts = $posts->orderBy('created_at', 'desc')->paginate(15);

        // If we're not on the default language, apply translations to the posts
        if ($currentLocale !== $defaultLocale) {
            $posts->getCollection()->transform(function ($post) use ($currentLocale) {
                $translation = $post->translation($currentLocale);
                if ($translation) {
                    $post->title = $translation->title;
                    $post->content = $translation->content;
                    $post->meta = $translation->meta;
                    $post->header = $translation->header;
                }
                return $post;
            });
        }

        return $posts;
    }

    public static function updateLangJsonFiles() {
        try {
            $updatesPath = base_path('lang/updates');
            $langPath = base_path('lang');

            // Check if updates directory exists
            if (!is_dir($updatesPath)) {
                return false;
            }

            // Get all JSON files from updates directory
            $updateFiles = glob($updatesPath . '/*.json');

            if (empty($updateFiles)) {
                return false;
            }

            foreach ($updateFiles as $updateFile) {
                $fileName = basename($updateFile);
                $targetFile = $langPath . '/' . $fileName;

                // Load update strings
                $updateContent = file_get_contents($updateFile);
                $updateStrings = json_decode($updateContent, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Invalid JSON in update file: ' . $updateFile);
                    continue;
                }

                // Check if target language file exists
                if (!file_exists($targetFile)) {
                    // Create new language file with all update strings
                    $newContent = json_encode($updateStrings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    file_put_contents($targetFile, $newContent);
                    Log::info('Created new language file: ' . $targetFile);
                    continue;
                }

                // Load existing language file
                $existingContent = file_get_contents($targetFile);
                $existingStrings = json_decode($existingContent, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Invalid JSON in existing language file: ' . $targetFile);
                    continue;
                }

                // Track if any new strings were added
                $hasNewStrings = false;

                // Merge new strings from updates
                foreach ($updateStrings as $key => $value) {
                    if (!array_key_exists($key, $existingStrings)) {
                        $existingStrings[$key] = $value;
                        $hasNewStrings = true;
                    }
                }

                // Save updated file only if new strings were added
                if ($hasNewStrings) {
                    // Sort keys alphabetically for better organization
                    ksort($existingStrings);
                    $updatedContent = json_encode($existingStrings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    file_put_contents($targetFile, $updatedContent);
                }
            }

            return true;
        } catch (Exception $e) {
            Log::error('Error updating language files: ' . $e->getMessage());
            return false;
        }
    }
}
