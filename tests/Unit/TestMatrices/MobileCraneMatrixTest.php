<?php

use App\Enums\TestMatrixType;
use App\TestMatrices\MobileCraneMatrix;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidatorFactory;

it('resolves the view names with the mc suffix', function () {
    $matrix = TestMatrixType::MOBILE_CRANE->matrix();

    expect($matrix)->toBeInstanceOf(MobileCraneMatrix::class)
        ->and($matrix->formView())->toBe('livewire.inspections.test-matrix.mc')
        ->and($matrix->pdfView())->toBe('pdf.test-matrix.mc');
});

it('defaults the slewing angle to R', function () {
    expect((new MobileCraneMatrix)->blankRow())
        ->toHaveKeys((new MobileCraneMatrix)->fields())
        ->slewing_angle->toBe('R')
        ->setup->toBeNull();
});

it('ignores default values when determining filled rows', function () {
    $rows = (new MobileCraneMatrix)->filledRows([
        ['slewing_angle' => 'R'],
        ['slewing_angle' => 'A'],
        ['slewing_angle' => 'R', 'setup' => '1/1'],
    ]);

    expect($rows)->toHaveCount(2)
        ->and($rows[0]['slewing_angle'])->toBe('A')
        ->and($rows[1]['setup'])->toBe('1/1');
});

it('calculates the LMB and LB deviations of a row', function () {
    $deviations = (new MobileCraneMatrix)->deviations([
        'test_load' => '10,5',
        'lmb_permissible_load' => '10',
        'lb_triggered_at' => '11',
        'lb_permissible_load' => '10',
    ]);

    expect($deviations['lmb'])->toEqualWithDelta(5.0, 0.001)
        ->and($deviations['lb'])->toEqualWithDelta(10.0, 0.001);
});

it('validates the setup option', function (?string $setup, bool $passes) {
    $validator = (new ValidatorFactory(new Translator(new ArrayLoader, 'nl')))->make(
        ['rows' => [['setup' => $setup]]],
        (new MobileCraneMatrix)->rules(),
    );

    expect($validator->passes())->toBe($passes);
})->with([
    'empty' => [null, true],
    'B' => ['B', true],
    'fraction' => ['3/4', true],
    'var' => ['var', true],
    'unknown' => ['X', false],
]);
