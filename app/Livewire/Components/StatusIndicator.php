<?php

namespace App\Livewire\Components;

use Livewire\Component;

class StatusIndicator extends Component
{
    public ?string $status = 'normal';

    protected $listeners = ['sensor-data-received' => 'updateStatus'];

    public function mount(?string $status = 'normal')
    {
        $this->status = $status ?? 'normal';
    }

    public function updateStatus($data = null)
    {
        if (!is_array($data) || !isset($data['status'])) {
            $this->status = 'normal';
            return;
        }

        $this->status = $data['status'];
    }

    public function render()
    {
        return view('livewire.components.status-indicator');
    }
}
