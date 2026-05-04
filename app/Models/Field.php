<?php

namespace App\Models;

use App\Enums\FieldType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Field extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'attrs' => 'json:unicode',
        'type' => FieldType::class,
    ];

    public function numberedLabel(): Attribute
    {
        return new Attribute(
            get: fn () => '<span>' . $this->number . '</span> · ' . $this->label,
        );
    }

    public function options(): Attribute
    {
        return new Attribute(
            get: fn () => explode("\n", $this->values) ?? [],
        );
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query
            ->where(function ($query) use ($search) {
                $query->where('label', 'like', '%' . $search . '%')
                      ->orWhere('number', 'like', '%' . $search . '%');
            })
            ->orderBy('label');
    }

    /**
     * Relationships
     */

    public function fieldGroups(): BelongsToMany
    {
        return $this->belongsToMany(FieldGroup::class, 'field_form')
            ->withPivot('id', 'form_id', 'field_group_id', 'position')
            ->orderBy('position');
    }

    public function forms(): BelongsToMany
    {
        return $this->belongsToMany(Form::class)
            ->withPivot('id', 'field_group_id', 'position')
            ->orderBy('position');
    }
}