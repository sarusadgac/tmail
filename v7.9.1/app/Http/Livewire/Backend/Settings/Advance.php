<?php

namespace App\Http\Livewire\Backend\Settings;

use Livewire\Component;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Advance extends Component {
    /**
     * Components State
     */
    public $state = [
        'lock' => [
            'enable' => false,
            'text' => '',
            'password' => ''
        ],
        'global' => [
            'css' => '',
            'js' => '',
            'header' => '',
            'footer' => ''
        ],
        'api_keys' => [],
        'languages' => [],
    ];

    public function mount() {
        $this->state['lock'] = config('app.settings.lock');
        $this->state['global'] = config('app.settings.global');
        $this->state['api_keys'] = config('app.settings.api_keys');
        $this->state['languages'] = config('app.settings.languages');
    }

    public function add() {
        array_push($this->state['api_keys'], $this->random());
    }

    public function remove($key) {
        unset($this->state['api_keys'][$key]);
    }

    public function enableLanguage($language) {
        array_push($this->state['languages'], $language);
    }

    public function disableLanguage($language) {
        $index = array_search($language, $this->state['languages']);
        if ($this->state['languages'][$index] == $language) {
            unset($this->state['languages'][$index]);
            $this->state['languages'] = array_values($this->state['languages']);
        }
    }

    private function random($length = 20) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($characters), 0, $length);
    }

    public function update() {
        $this->validate(
            [
                'state.api_keys.*' => 'required'
            ],
            [
                'state.api_keys.*.required' => 'API Key field is Required'
            ]
        );
        $settings = Setting::whereIn('key', ['lock', 'global', 'api_keys', 'languages'])->get();
        foreach ($settings as $setting) {
            $setting->value = serialize($this->state[$setting->key]);
            $setting->save();
        }
        $this->emit('saved');
    }

    public function render() {
        return view('backend.settings.advance');
    }
}
