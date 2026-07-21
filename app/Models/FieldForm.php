<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FieldForm extends Pivot
{
    protected $guarded = ['id'];

    public $incrementing = true;

    protected $table = 'field_form';

    public $timestamps = false;

    public function fieldGroup(): BelongsTo
    {
        return $this->belongsTo(FieldGroup::class);
    }
}
