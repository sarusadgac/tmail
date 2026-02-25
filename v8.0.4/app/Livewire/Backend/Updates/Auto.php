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
        $status = Cache::get('app-update');
        if ($status && !isset($status['error'])) {
            $this->status = $status;
            if (!isset($status['available'])) {
                $this->status['available'] = false;
                $this->status['disabled'] = false;
                $this->status['message'] = 'No Update Available';
            }
        }
    }

    public function checkForUpdates() {
        // Check for updates disabled - v8.0.4 license bypass
        $this->status = ['error' => true];
        $this->status['available'] = false;
        $this->status['disabled'] = false;
        $this->status['message'] = 'Update check disabled';
    }

    public function apply($step = 0) {
        // Auto update disabled - v8.0.4 license bypass
        $this->progress .= '<div class="text-red-600">Update system is disabled in this installation</div>';
    }

    public function render() {
        return view('backend.updates.auto');
    }
}
