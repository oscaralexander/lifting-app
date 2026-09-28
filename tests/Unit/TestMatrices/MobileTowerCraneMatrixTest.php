<?php

use App\Enums\TestMatrixType;
use App\TestMatrices\MobileTowerCraneMatrix;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidatorFactory;

it('resolves the view names with the mtc suffix', function () {
    $matrix = TestMatrixType::MOBILE_TOWER_CRANE->matrix();

    expect($matrix)->toBeInstanceOf(MobileTowerCraneMatrix::class)
        ->and($matrix->formView())->toBe('livewire.inspections.test-matrix.mtc')
        ->and($matrix->pdfView())->toBe('pdf.test-matrix.mtc');
});

it('defaults the slewing angle to R', function () {
    expect((new MobileTowerCraneMatrix)->blankRow())
        ->toHaveKeys((new MobileTowerCraneMatrix)->fields())
        ->slewing_angle->toBe('R')
        ->setup->toBeNull();
});

it('calculates the LMB and LB deviations of a row', function () {
    $deviations = (new MobileTowerCraneMatrix)->deviations([
        'test_load' => '4,9',
        'lmb_permissible_load' => '4,8',
        'lb_triggered_at' => '8,3',
        'lb_permissible_load' => '8',
    ]);

    expect($deviations['lmb'])->toEqualWithDelta(2.083, 0.001)
        ->and($deviations['lb'])->toEqualWithDelta(3.75, 0.001);
});

it('validates the setup option', function (?string $setup, bool $passes) {
    $validator = (new ValidatorFactory(new Translator(new ArrayLoader, 'nl')))->make(
        ['rows' => [['setup' => $setup]]],
        (new MobileTowerCraneMatrix)->rules(),
    );

    expect($validator->passes())->toBe($passes);
})->with([
    'empty' => [null, true],
    'R' => ['R', true],
    'unknown' => ['X', false],
]);
