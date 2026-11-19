<?php

use App\Models\Inspection;
use Illuminate\Support\Carbon;
use Tests\TestCase;

uses(TestCase::class);

function makeInspection(array $attributes = [], array $attachmentInspections = []): Inspection
{
    $inspection = new Inspection($attributes);
    $inspection->setRelation('attachmentInspections', collect($attachmentInspections));

    return $inspection;
}

it('only allows deleting pending inspections', function (array $attributes, bool $isDeletable) {
    expect(makeInspection($attributes)->isDeletable())->toBe($isDeletable);
})->with([
    'pending' => [['is_completed' => false], true],
    'approved' => [['is_completed' => true, 'is_approved' => true], false],
    'rejected' => [['is_completed' => true, 'is_approved' => false], false],
    'cat A deficiencies' => [['is_completed' => true, 'has_cat_a_deficiencies' => true], false],
    'cat B deficiencies' => [['is_completed' => true, 'has_cat_b_deficiencies' => true], false],
]);

it('allows deleting a pending inspection whose attachment inspections are all pending', function () {
    $inspection = makeInspection(['is_completed' => false], [
        makeInspection(['is_completed' => false]),
        makeInspection(['is_completed' => false]),
    ]);

    expect($inspection->isDeletable())->toBeTrue();
});

it('does not allow deleting a pending inspection with a completed attachment inspection', function () {
    $inspection = makeInspection(['is_completed' => false], [
        makeInspection(['is_completed' => false]),
        makeInspection(['is_completed' => true, 'is_approved' => true]),
    ]);

    expect($inspection->isDeletable())->toBeFalse();
});

it('only allows archiving completed inspections', function (array $attributes, bool $isArchivable) {
    expect(makeInspection($attributes)->isArchivable())->toBe($isArchivable);
})->with([
    'pending' => [['is_completed' => false], false],
    'approved' => [['is_completed' => true, 'is_approved' => true], true],
    'rejected' => [['is_completed' => true, 'is_approved' => false], true],
    'cat A deficiencies' => [['is_completed' => true, 'has_cat_a_deficiencies' => true], true],
]);

it('does not allow archiving an inspection that is already archived', function () {
    $inspection = makeInspection(['is_completed' => true, 'is_approved' => true, 'archived_at' => Carbon::now()]);

    expect($inspection->isArchivable())->toBeFalse();
});

it('only allows restoring archived inspections', function (array $attributes, bool $isRestorable) {
    expect(makeInspection($attributes)->isRestorable())->toBe($isRestorable);
})->with([
    'archived' => [['is_completed' => true, 'is_approved' => true, 'archived_at' => Carbon::now()], true],
    'not archived' => [['is_completed' => true, 'is_approved' => true], false],
    'pending' => [['is_completed' => false], false],
]);
