<?php

namespace App\Livewire\Backend\Updates;

use App\Models\Setting;
use App\Services\Util;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Auto extends Component {

    public $status = [
        'available' => false,
        'disabled' => false,
        'message' => 'No Update Available'
    ];

    public $progress = '';

    protected $listeners = ['apply'];

    public function mount() {
        // Auto update service disabled - v8.0.2
        $this->status = [
            'available' => false,
            'disabled' => true,
            'message' => 'Auto update service disabled - License system removed for v8.0.2',
        ];
    }

    public function checkForUpdates() {
        Util::checkForAppUpdate();
    }

    /** Apply Update - DISABLED for v8.0.2 */
    public function apply($step = 0) {
        // Auto update system disabled - v8.0.2
        $this->progress .= '<div class="text-yellow-500">Auto update system disabled for v8.0.2</div>';
        $this->progress .= '<div class="text-white">License system removed - Manual updates only</div>';
        $this->progress .= '<div class="text-white">Please update manually by uploading new files and running migrations.</div>';
    }

    public function render() {
        return view('backend.updates.auto');
    }
}
