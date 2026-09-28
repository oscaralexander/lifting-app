<?php

namespace App\Livewire\Inspections;

use App\Constants\Event;
use App\Models\Inspection;
use App\TestMatrices\TestMatrix as TestMatrixDefinition;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class TestMatrix extends Component
{
    #[Locked]
    public string $inspectionHash;

    /**
     * @var list<array<string, string|null>>
     */
    public array $rows = [];

    #[Computed]
    public function inspection(): Inspection
    {
        return Inspection::with([
            'inspectionObject',
            'inspectable',
        ])
            ->where('hash', $this->inspectionHash)
            ->firstOrFail();
    }

    #[Computed]
    public function testMatrix(): ?TestMatrixDefinition
    {
        return $this->inspection->testMatrix();
    }

    public function mount(string $inspectionHash): void
    {
        $this->inspectionHash = $inspectionHash;
        $this->rows = $this->inspection->testMatrixRows();
    }

    public function render(): View
    {
        return view($this->testMatrix ? 'livewire.inspections.test-matrix.index' : 'livewire.inspections.test-matrix.none');
    }

    public function addRow(): void
    {
        $testMatrix = $this->testMatrix;

        if (! $testMatrix || count($this->rows) >= $testMatrix->maxRows()) {
            return;
        }

        $this->rows[] = $testMatrix->blankRow();
    }

    #[On(Event::SAVE_MATRIX)]
    public function save(): void
    {
        $testMatrix = $this->testMatrix;

        if (! $testMatrix) {
            return;
        }

        $this->validate([
            'rows' => ['array'],
            'rows.*.*' => ['nullable', 'string', 'max:255'],
        ]);

        $rows = $testMatrix->filledRows($this->rows);

        $inspection = $this->inspection;
        $inspection->matrix = $rows === [] ? null : ['type' => $testMatrix->type()->value, 'rows' => $rows];
        $inspection->save();

        $this->rows = $testMatrix->normalize($rows);

        unset($this->inspection, $this->testMatrix);
    }
}
