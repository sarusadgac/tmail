<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use App\Services\Util;

class SetSettingsToConfig {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        if (Util::checkDatabaseConnection()) {
            $options = Schema::hasTable((new Setting)->getTable()) ? Setting::get() : [];
            foreach ($options as $option) {
                config([
                    'app.settings.' . $option->key => unserialize($option->value)
                ]);
            }
        }
        return $next($request);
    }
}
