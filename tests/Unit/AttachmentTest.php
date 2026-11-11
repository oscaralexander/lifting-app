<?php

use App\Enums\InspectionObject\Attachment\Type as AttachmentType;
use App\Enums\InspectionObject\Type;
use App\Lib\InspectionResult;
use App\Models\Inspection;
use App\Models\InspectionObject;
use Tests\TestCase;

uses(TestCase::class);

it('only treats the attachment type as an attachment', function (Type $type, bool $isAttachment) {
    expect($type->isAttachment())->toBe($isAttachment);
})->with([
    'crane' => [Type::CRANE, false],
    'operator lift' => [Type::OPERATOR_LIFT, false],
    'attachment' => [Type::ATTACHMENT, true],
]);

it('links attachments only to cranes and operator lifts', function () {
    expect(Type::mainObjectTypes())->toBe([Type::CRANE, Type::OPERATOR_LIFT]);
});

it('labels the attachment types', function () {
    expect(AttachmentType::options())->toBe([
        'fork_carriage' => 'Vorkenbord',
        'rotating_fork_carriage' => 'Roterend vorkenbord',
        'fixed_jib' => 'Vaste jib',
        'telescopic_jib' => 'Tele jib',
        'winch_jib' => 'Lier jib/kop',
        'material_bucket' => 'Materiaalbak',
        'aerial_work_platform_bucket' => 'Hoogwerkerbak',
        'other' => 'Anders…',
    ]);
});

it('describes an object by its most specific type', function (array $attributes, string $label) {
    expect((new InspectionObject($attributes))->type_label)->toBe($label);
})->with([
    'crane' => [['type' => 'crane'], 'Kraan'],
    'attachment' => [['type' => 'attachment', 'attachment_type' => 'fork_carriage'], 'Vorkenbord'],
    'other attachment' => [['type' => 'attachment', 'attachment_type' => 'other', 'attachment_type_other' => 'Hydraulische hamer'], 'Hydraulische hamer'],
    'other attachment without description' => [['type' => 'attachment', 'attachment_type' => 'other'], 'Anders…'],
    'attachment without attachment type' => [['type' => 'attachment'], 'Verwisselbaar hulpstuk'],
]);

it('knows whether an inspection is of an attachment', function (string $type, bool $isAttachment) {
    $inspection = new Inspection;
    $inspection->setRelation('inspectionObject', new InspectionObject(['type' => $type]));

    expect($inspection->isAttachment())->toBe($isAttachment);
})->with([
    'crane' => ['crane', false],
    'attachment' => ['attachment', true],
]);

it('does not treat an inspection without object as an attachment', function () {
    $inspection = new Inspection;
    $inspection->setRelation('inspectionObject', null);

    expect($inspection->isAttachment())->toBeFalse();
});

it('summarises an approved inspection', function () {
    expect(InspectionResult::html(new Inspection(['is_approved' => true])))->toBe('Goedgekeurd');
});

it('summarises a rejected inspection with its deficiencies', function () {
    $inspection = new Inspection([
        'is_approved' => false,
        'has_cat_a_deficiencies' => true,
        'requires_reinspection' => true,
    ]);

    expect(InspectionResult::html($inspection))->toBe(implode('<br>', [
        e(__('enums/inspection_status.rejected')),
        e(__('models/inspection.has_cat_a_deficiencies.label')),
        e(__('models/inspection.requires_reinspection.label')),
    ]));
});
