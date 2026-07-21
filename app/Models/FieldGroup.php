<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldGroup extends Model
{
    protected $fillable = [
        'form_id',
        'name',
        'number',
        'position',
    ];

    public $timestamps = false;

    protected $with = [
        'fields',
        'formComments',
    ];

    public function numberedName(): Attribute
    {
        return new Attribute(
            get: fn () => '<b>'.$this->number.'</b> · '.$this->name,
        );
    }

    public function fieldForms(): HasMany
    {
        return $this->hasMany(FieldForm::class);
    }

    public function fields(): BelongsToMany
    {
        return $this->belongsToMany(
            Field::class,
            'field_form',
            'field_group_id',
            'field_id'
        )
            ->withPivot('id', 'form_id', 'field_group_id', 'position', 'required')
            ->orderBy('field_form.position');
    }

    public function formComments(): HasMany
    {
        return $this->hasMany(FormComment::class)->orderBy('position');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
