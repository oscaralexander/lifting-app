<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Settings;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public array $settings = [];

    public function mount(): void
    {
        $this->settings = Settings::all()->keyBy('key')->pluck('value', 'key')->toArray();
    }

    public function render(): View
    {
        return view('livewire.admin.settings.index');
    }

    public function rules(): array
    {
        return [
            'settings.passcode' => 'required|string|min:4|max:4|regex:/^\d+$/',
            'settings.service_no_nl' => 'required|regex:/^\+?[0-9\s]+$/',
            'settings.service_no_be' => 'required|regex:/^\+?[0-9\s]+$/',
            'settings.service_no_fr' => 'required|regex:/^\+?[0-9\s]+$/',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        foreach ($this->settings as $key => $value) {
            Settings::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->dispatch('toast', message: __('settings.toast.saved'), type: 'success');
    }
}
