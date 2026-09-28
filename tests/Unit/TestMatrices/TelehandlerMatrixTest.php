<?php

use App\Enums\TestMatrixType;
use App\TestMatrices\TelehandlerMatrix;

it('resolves the view names with the th suffix', function () {
    $matrix = TestMatrixType::TELEHANDLER->matrix();

    expect($matrix)->toBeInstanceOf(TelehandlerMatrix::class)
        ->and($matrix->formView())->toBe('livewire.inspections.test-matrix.th')
        ->and($matrix->pdfView())->toBe('pdf.test-matrix.th');
});

it('defaults the slewing angle to R', function () {
    expect((new TelehandlerMatrix)->blankRow())
        ->toHaveKeys((new TelehandlerMatrix)->fields())
        ->slewing_angle->toBe('R');
});

it('judges the LMB and LB deviations against the fixed maximum', function () {
    $matrix = new TelehandlerMatrix;
    $row = [
        'test_load' => '4,2',
        'lmb_permissible_load' => '4',
        'lb_triggered_at' => '4,5',
        'lb_permissible_load' => '4',
    ];

    expect($matrix->deviations($row)['lmb'])->toEqualWithDelta(5.0, 0.001)
        ->and($matrix->deviations($row)['lb'])->toEqualWithDelta(12.5, 0.001)
        ->and($matrix->approvals($row))->toBe(['lmb' => true, 'lb' => false])
        ->and($matrix->isApproved($row))->toBeFalse();
});
