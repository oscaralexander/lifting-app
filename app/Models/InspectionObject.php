<?php

namespace App\Models;

use App\Enums\InspectionObject\Type;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspectionObject extends Model
{
    use SoftDeletes;

    public const PER_PAGE = 25;

    protected $casts = [
        'type' => Type::class,
        'year_manufacture' => 'integer',
    ];

    protected $guarded = ['id'];

    /**
     * Attributes
     */
    public function name(): Attribute
    {
        return new Attribute(
            get: fn () => collect([$this->manufacturer, $this->model])->filter()->implode(' '),
        );
    }

    /**
     * Relationships
     */
    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }
}
