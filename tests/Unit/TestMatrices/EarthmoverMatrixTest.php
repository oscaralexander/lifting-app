<?php

use App\Enums\TestMatrixType;
use App\TestMatrices\EarthmoverMatrix;

it('resolves the view names with the em suffix', function () {
    $matrix = TestMatrixType::EARTHMOVER->matrix();

    expect($matrix)->toBeInstanceOf(EarthmoverMatrix::class)
        ->and($matrix->formView())->toBe('livewire.inspections.test-matrix.em')
        ->and($matrix->pdfView())->toBe('pdf.test-matrix.em');
});

it('defaults the slewing angle to R', function () {
    expect((new EarthmoverMatrix)->blankRow())
        ->toHaveKeys((new EarthmoverMatrix)->fields())
        ->slewing_angle->toBe('R');
});

it('judges the LMB and LB deviations against the fixed maximum', function () {
    $matrix = new EarthmoverMatrix;
    $row = [
        'test_load' => '3,15',
        'lmb_permissible_load' => '3',
        'lb_triggered_at' => '3,3',
        'lb_permissible_load' => '3',
    ];

    expect($matrix->deviations($row)['lmb'])->toEqualWithDelta(5.0, 0.001)
        ->and($matrix->deviations($row)['lb'])->toEqualWithDelta(10.0, 0.001)
        ->and($matrix->approvals($row))->toBe(['lmb' => true, 'lb' => false])
        ->and($matrix->isApproved($row))->toBeFalse();
});
