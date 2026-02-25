<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Services\Util;

class SettingSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $settings = new \stdClass;
        $settings->name = 'TMail';
        $settings->version = '7.9.1';
        $settings->logo = '';
        $settings->favicon = '';
        $settings->license_key = 'tmail-v791-license-bypassed';
        $settings->api_keys = [];
        $settings->domains = [];
        $settings->default_domain = 0;
        $settings->homepage = 0;
        $settings->app_header = '';
        $settings->theme = 'default';
        $settings->fetch_seconds = 20;
        $settings->email_limit = 5;
        $settings->fetch_messages_limit = 15;
        $settings->ads = [
            'one' => '',
            'two' => '',
            'three' => '',
            'four' => '',
            'five' => '',
        ];
        $settings->socials = [];
        $settings->colors = [
            'primary' => '#0155b5',
            'secondary' => '#2fc10a',
            'tertiary' => '#d2ab3e'
        ];
        $settings->engine = 'imap';
        $settings->delivery = [
            'key' => Util::generateRandomString(32),
        ];
        $settings->imap = [
            'host' => 'localhost',
            'port' => 993,
            'encryption' => 'ssl',
            'validate_cert' => true,
            'username' => 'username',
            'password' => 'password',
            'default_account' => 'default',
            'protocol' => 'imap',
            'cc_check' => false,
        ];
        $settings->language = 'en';
        $settings->enable_create_from_url = false;
        $settings->forbidden_ids = [
            'admin',
            'catch'
        ];
        $settings->blocked_domains = [];
        $settings->cron_password = str_shuffle('6789abcdefghijklmnopqrstuvwxy');
        $settings->delete = [
            'value' => 1,
            'key' => 'd'
        ];
        $settings->custom = [
            'min' => 3,
            'max' => 15
        ];
        $settings->random = [
            'start' => 0,
            'end' => 0
        ];
        $settings->global = [
            'css' => '',
            'js' => '',
            'header' => '',
            'footer' => ''
        ];
        $settings->cookie = [
            'enable' => true,
            'text' => '<p>By using this website you agree to our <a href="#" target="_blank">Cookie Policy</a></p>'
        ];
        $settings->disable_used_email = false;
        $settings->allow_reuse_email_in_days = 7;
        $settings->captcha = 'off'; //Options - off|recaptcha2|recaptcha3|hcaptcha
        $settings->recaptcha2 = [
            'site_key' => '',
            'secret_key' => ''
        ];
        $settings->recaptcha3 = [
            'site_key' => '',
            'secret_key' => ''
        ];
        $settings->hcaptcha = [
            'site_key' => '',
            'secret_key' => ''
        ];
        $settings->after_last_email_delete = 'redirect_to_homepage';
        $settings->date_format = 'd M Y h:i A';
        $settings->theme_options = [
            'mailbox_page' => 0,
            'button' => [
                'text' => 'Create your own Temp Mail',
                'link' => 'https://1.envato.market/tmail',
            ]
        ];
        $settings->disable_mailbox_slug = false;
        $settings->external_link_masker = '';
        $settings->add_mail_in_title = true;
        $settings->enable_ad_block_detector = false;
        $settings->font_family = [
            'head' => 'Kadwa',
            'body' => 'Poppins',
        ];
        $settings->lock = [
            'enable' => false,
            'text' => '',
            'password' => str_shuffle('1234567890abcdefghijklmnopqrstuvwxy')
        ];
        $settings->allowed_file_types = 'csv,doc,docx,xls,xlsx,ppt,pptx,xps,pdf,dxf,ai,psd,eps,ps,svg,ttf,zip,rar,tar,gzip,mp3,mpeg,wav,ogg,jpeg,jpg,png,gif,bmp,tif,webm,mpeg4,3gpp,mov,avi,mpegs,wmv,flx,txt';
        $settings->languages = ['ar', 'de', 'en', 'fr', 'hi', 'pl', 'ru', 'es', 'vi', 'tr', 'no', 'id', 'it'];

        foreach ($settings as $key => $value) {
            if (!Setting::where('key', $key)->exists()) {
                Setting::create([
                    'key' => $key,
                    'value' => serialize($value)
                ]);
            }
        }
    }
}
