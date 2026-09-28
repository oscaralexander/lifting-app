<?php

use App\Enums\TestMatrixType;
use App\TestMatrices\LoaderCraneMatrix;

it('resolves the view names with the lc suffix', function () {
    $matrix = TestMatrixType::LOADER_CRANE->matrix();

    expect($matrix)->toBeInstanceOf(LoaderCraneMatrix::class)
        ->and($matrix->formView())->toBe('livewire.inspections.test-matrix.lc')
        ->and($matrix->pdfView())->toBe('pdf.test-matrix.lc');
});

it('defaults the slewing angle to R', function () {
    expect((new LoaderCraneMatrix)->blankRow())
        ->toHaveKeys((new LoaderCraneMatrix)->fields())
        ->slewing_angle->toBe('R');
});

it('calculates the LMB and LB deviations of a row', function () {
    $deviations = (new LoaderCraneMatrix)->deviations([
        'test_load' => '2,2',
        'lmb_permissible_load' => '2',
        'lb_triggered_at' => '5,25',
        'lb_permissible_load' => '5',
    ]);

    expect($deviations['lmb'])->toEqualWithDelta(10.0, 0.001)
        ->and($deviations['lb'])->toEqualWithDelta(5.0, 0.001);
});

it('limits the LMB deviation by the entered maximum', function (?string $maxDeviation, ?bool $expected) {
    $approvals = (new LoaderCraneMatrix)->approvals([
        'test_load' => '2.3',
        'lmb_permissible_load' => '2',
        'lmb_max_deviation' => $maxDeviation,
    ]);

    expect($approvals['lmb'])->toBe($expected);
})->with([
    'deviation below the maximum' => ['20', true],
    'deviation equal to the maximum' => ['15', true],
    'deviation above the maximum' => ['12,5', false],
    'no maximum entered' => [null, null],
    'maximum is not a number' => ['x', null],
]);

it('keeps the fixed maximum for the LB deviation', function () {
    $approvals = (new LoaderCraneMatrix)->approvals([
        'lb_triggered_at' => '11',
        'lb_permissible_load' => '10',
        'lmb_max_deviation' => '20',
    ]);

    expect($approvals['lb'])->toBeFalse();
});

it('approves a row based on both deviations', function (array $row, ?bool $expected) {
    expect((new LoaderCraneMatrix)->isApproved($row))->toBe($expected);
})->with([
    'nothing to judge' => [['test_load' => '2.3', 'lmb_permissible_load' => '2'], null],
    'LMB within the maximum' => [['test_load' => '2.3', 'lmb_permissible_load' => '2', 'lmb_max_deviation' => '18'], true],
    'LMB above the maximum' => [['test_load' => '2.3', 'lmb_permissible_load' => '2', 'lmb_max_deviation' => '12'], false],
    'LMB within, LB above' => [[
        'test_load' => '2.3',
        'lmb_permissible_load' => '2',
        'lmb_max_deviation' => '18',
        'lb_triggered_at' => '11',
        'lb_permissible_load' => '10',
    ], false],
]);
