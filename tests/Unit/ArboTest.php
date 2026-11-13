<?php

use App\Enums\InspectionObject\Type;
use App\Models\Form;
use App\Models\Inspection;
use App\Models\InspectionObject;
use Tests\TestCase;

uses(TestCase::class);

it('labels the arbo object type', function () {
    expect(Type::ARBO->label())->toBe('Arbobesluit 7.4a')
        ->and(Type::options())->toHaveKey('arbo', 'Arbobesluit 7.4a');
});

it('only treats cranes and operator lifts as main objects', function (Type $type, bool $isMainObject) {
    expect($type->isMainObject())->toBe($isMainObject);
})->with([
    'crane' => [Type::CRANE, true],
    'operator lift' => [Type::OPERATOR_LIFT, true],
    'attachment' => [Type::ATTACHMENT, false],
    'arbo' => [Type::ARBO, false],
]);

it('only inspects attachments and arbo objects periodically', function (string $type, bool $isAlwaysPeriodical) {
    $inspection = new Inspection;
    $inspection->setRelation('inspectionObject', new InspectionObject(['type' => $type]));

    expect($inspection->isAlwaysPeriodical())->toBe($isAlwaysPeriodical);
})->with([
    'crane' => ['crane', false],
    'operator lift' => ['operator_lift', false],
    'attachment' => ['attachment', true],
    'arbo' => ['arbo', true],
]);

it('does not treat an arbo object as an attachment', function () {
    $inspection = new Inspection;
    $inspection->setRelation('inspectionObject', new InspectionObject(['type' => 'arbo']));

    expect($inspection->isAttachment())->toBeFalse();
});

it('describes a schema by its most specific type', function (array $attributes, ?string $label) {
    expect((new Form($attributes))->type_label)->toBe($label);
})->with([
    'crane with crane type' => [['type' => 'crane', 'crane_type' => 'tower_crane'], 'Torenkraan'],
    'crane without crane type' => [['type' => 'crane'], 'Kraan'],
    'operator lift' => [['type' => 'operator_lift'], 'Machinistenlift'],
    'arbo' => [['type' => 'arbo'], 'Arbobesluit 7.4a'],
    'without type' => [[], null],
]);
