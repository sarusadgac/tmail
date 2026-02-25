<?php

namespace App\Http\Livewire\Backend\Update;

use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Artisan;
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
        $this->check();
    }

    public function apply($step = 0) {
        // Auto update system disabled - v7.9.1
        $this->progress .= '<div class="text-yellow-500">Auto update system disabled for v7.9.1</div>';
        $this->progress .= '<div class="text-white">License system removed - Manual updates only</div>';
    }

    public function render() {
        return view('backend.update.auto');
    }

    /** Check for Update - License control removed */
    private function check() {
        // Auto update service disabled - v7.9.1
        $this->status = [
            'available' => false,
            'disabled' => true,
            'message' => 'Auto update service disabled - License system removed for v7.9.1',
        ];
        return false;
    }
}
