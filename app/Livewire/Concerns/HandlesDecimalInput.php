<?php

namespace App\Livewire\Concerns;

trait HandlesDecimalInput
{
    public function updated(string $name, mixed $value): void
    {
        if (! in_array($name, $this->decimalProperties ?? [])) {
            return;
        }

        if (is_string($value) && $value !== '') {
            data_set($this, $name, str_replace(',', '.', $value));
        }
    }

    protected function normalizeDecimalInputs(): void
    {
        foreach ($this->decimalProperties ?? [] as $property) {
            $value = data_get($this, $property);

            if (is_string($value) && $value !== '') {
                data_set($this, $property, str_replace(',', '.', $value));
            }
        }
    }
}
