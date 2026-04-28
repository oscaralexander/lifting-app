<?php

namespace App\Livewire\Inspections;

use App\Constants\Event;
use App\Models\Inspection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class TestMatrix extends Component
{
    #[Locked]
    public string $inspectionHash;

    public array $matrix = [];

    #[Computed]
    public function inspection(): Inspection
    {
        return Inspection::with('inspectable')
            ->where('hash', $this->inspectionHash)
            ->firstOrFail();
    }

    public function mount(string $inspectionHash): void
    {
        $this->inspectionHash = $inspectionHash;

        $stored = $this->inspection->matrix ?? [];

        $this->matrix = array_map(
            fn (int $i) => array_merge($this->blankRow(), (array) ($stored[$i] ?? [])),
            range(0, 9),
        );
    }

    public function render(): View
    {
        return view('livewire.inspections.test-matrix');
    }

    #[On(Event::SAVE_MATRIX)]
    public function save(): void
    {
        $inspection = $this->inspection;
        $inspection->matrix = $this->matrix;
        $inspection->save();

        unset($this->inspection);
    }

    private function blankRow(): array
    {
        return [
            'col_8' => null, // Aantal parten hijskabel
            'col_9' => null, // Zwenkhoek
            'col_10' => null, // LMB code
            'col_11' => null, // Gang (snelheid)
            'col_12' => null, // Toelaatbare bedrijfslast kolom 17
            'col_13' => null, // Toelaatbare bedrijfslast kolom 15 (max. vlucht)
            'col_14' => null, // LMB
            'col_15_1' => null, // LB
            'col_15_2' => null, // Afwijking LB
            'col_16' => null, // Afwijking LMB (%)
            'col_17' => null, // LB treedt in werking bij (t/kg)
            'col_18' => null, // Afwijking LB (%)
        ];
    }
}
