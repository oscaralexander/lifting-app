<?php

use App\Enums\InspectionObject\Crane\Type as CraneType;
use App\Enums\TestMatrixType;
use App\Models\Inspection;
use App\Models\InspectionObjects\Crane;
use App\TestMatrices\TestMatrix;
use App\TestMatrices\TowerCraneMatrix;

function inspectionWithCrane(?CraneType $craneType, ?array $matrix = null): Inspection
{
    $inspection = new Inspection;
    $inspection->setRawAttributes(['matrix' => $matrix === null ? null : json_encode($matrix)]);
    $inspection->setRelation('inspectable', (new Crane)->forceFill(['type' => $craneType]));

    return $inspection;
}

it('parses numbers with a decimal comma or point', function (mixed $value, ?float $expected) {
    expect(TestMatrix::parseNumber($value))->toBe($expected);
})->with([
    'comma' => ['18,8', 18.8],
    'point' => ['18.8', 18.8],
    'padded' => [' 20 ', 20.0],
    'null' => [null, null],
    'empty' => ['', null],
    'text' => ['LM1', null],
]);

it('calculates the deviation from the permissible value', function () {
    expect(TestMatrix::deviation('18,1', '18,8'))->toEqualWithDelta(3.867, 0.001)
        ->and(TestMatrix::deviation('20', '18'))->toEqualWithDelta(-10.0, 0.001)
        ->and(TestMatrix::deviation('0', '18'))->toBeNull()
        ->and(TestMatrix::deviation(null, '18'))->toBeNull()
        ->and(TestMatrix::deviation('18', ''))->toBeNull();
});

it('calculates the LMB and LB deviations of a row', function () {
    $deviations = (new TowerCraneMatrix)->deviations([
        'test_load' => '18.8',
        'lmb_permissible_load' => '18.1',
        'lb_triggered_at' => '21.3',
        'lb_permissible_load' => '20.0',
    ]);

    expect($deviations['lmb'])->toEqualWithDelta(3.867, 0.001)
        ->and($deviations['lb'])->toEqualWithDelta(6.5, 0.001);
});

it('approves a row based on its deviations', function (array $row, ?bool $expected) {
    expect((new TowerCraneMatrix)->isApproved($row))->toBe($expected);
})->with([
    'no input' => [[], null],
    'incomplete input' => [['test_load' => '18.8'], null],
    'within limits' => [['test_load' => '10.9', 'lmb_permissible_load' => '10'], true],
    'at the limit' => [['test_load' => '11', 'lmb_permissible_load' => '10'], false],
    'LB exceeds, LMB within' => [[
        'test_load' => '10.5',
        'lmb_permissible_load' => '10',
        'lb_triggered_at' => '12',
        'lb_permissible_load' => '10',
    ], false],
    'negative deviation' => [['test_load' => '8', 'lmb_permissible_load' => '10'], true],
]);

it('judges each deviation against the fixed maximum', function () {
    $approvals = (new TowerCraneMatrix)->approvals([
        'test_load' => '10.999',
        'lmb_permissible_load' => '10',
        'lb_triggered_at' => '11',
        'lb_permissible_load' => '10',
    ]);

    expect($approvals)->toBe(['lmb' => true, 'lb' => false])
        ->and((new TowerCraneMatrix)->approvals([]))->toBe(['lmb' => null, 'lb' => null]);
});

it('formats deviations and approvals', function () {
    $matrix = new TowerCraneMatrix;

    expect($matrix->formatDeviation(3.8674))->toBe('3,87%')
        ->and($matrix->formatDeviation(null))->toBe('—')
        ->and($matrix->status(true))->toBe('passed')
        ->and($matrix->status(false))->toBe('failed')
        ->and($matrix->status(null))->toBe('neutral')
        ->and($matrix->formatApproval(true))->toBe('JA')
        ->and($matrix->formatApproval(false))->toBe('NEE')
        ->and($matrix->formatApproval(null))->toBe('—');
});

it('normalizes rows to the known fields', function () {
    $rows = (new TowerCraneMatrix)->normalize([
        ['test_load' => '18.8', 'unknown' => 'R'],
        4 => ['test_load' => '1'],
    ]);

    expect($rows)->toHaveCount(2)
        ->and($rows[0])->toHaveKeys((new TowerCraneMatrix)->fields())
        ->and($rows[0])->not->toHaveKey('unknown')
        ->and($rows[0]['test_load'])->toBe('18.8')
        ->and($rows[0]['lmb_code'])->toBeNull()
        ->and($rows[1]['test_load'])->toBe('1');
});

it('normalizes to at least one row', function () {
    expect((new TowerCraneMatrix)->normalize([]))->toBe([(new TowerCraneMatrix)->blankRow()]);
});

it('normalizes to at most the maximum number of rows', function () {
    $matrix = new TowerCraneMatrix;

    expect($matrix->normalize(array_fill(0, $matrix->maxRows() + 5, [])))->toHaveCount($matrix->maxRows());
});

it('keeps only the rows with a filled in value', function () {
    $rows = (new TowerCraneMatrix)->filledRows([
        ['lmb_code' => '', 'test_load' => null],
        ['lmb_code' => 'LM1'],
        [],
        ['test_load' => '0'],
    ]);

    expect($rows)->toHaveCount(2)
        ->and($rows[0]['lmb_code'])->toBe('LM1')
        ->and($rows[1]['test_load'])->toBe('0')
        ->and((new TowerCraneMatrix)->filledRows([]))->toBe([]);
});

it('resolves the view names from the matrix type', function () {
    $matrix = TestMatrixType::TOWER_CRANE->matrix();

    expect($matrix)->toBeInstanceOf(TowerCraneMatrix::class)
        ->and($matrix->formView())->toBe('livewire.inspections.test-matrix.tower-crane')
        ->and($matrix->pdfView())->toBe('pdf.test-matrix.tower-crane');
});

it('maps crane types to a test matrix type', function (CraneType $craneType, ?TestMatrixType $expected) {
    expect($craneType->testMatrixType())->toBe($expected);
})->with([
    'tower crane' => [CraneType::TOWER_CRANE, TestMatrixType::TOWER_CRANE],
    'mobile tower crane' => [CraneType::MOBILE_TOWER_CRANE, TestMatrixType::MOBILE_TOWER_CRANE],
    'mobile crane' => [CraneType::MOBILE_CRANE, TestMatrixType::MOBILE_CRANE],
    'loader crane' => [CraneType::LOADER_CRANE, TestMatrixType::LOADER_CRANE],
    'earthmover' => [CraneType::EARTHMOVER, null],
]);

it('resolves an inspection\'s test matrix from its crane type', function () {
    expect(inspectionWithCrane(CraneType::TOWER_CRANE)->testMatrix())->toBeInstanceOf(TowerCraneMatrix::class)
        ->and(inspectionWithCrane(CraneType::EARTHMOVER)->testMatrix())->toBeNull()
        ->and(inspectionWithCrane(null)->testMatrix())->toBeNull();
});

it('prefers the stored matrix type over the crane type', function () {
    $inspection = inspectionWithCrane(CraneType::EARTHMOVER, [
        'type' => 'tower_crane',
        'rows' => [['lmb_code' => 'LM1']],
    ]);

    expect($inspection->testMatrix())->toBeInstanceOf(TowerCraneMatrix::class)
        ->and($inspection->testMatrixRows())->toHaveCount(1)
        ->and($inspection->testMatrixRows()[0]['lmb_code'])->toBe('LM1');
});
