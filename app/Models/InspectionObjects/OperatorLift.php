<?php

namespace App\Models\InspectionObjects;

use App\Enums\InspectionObject\OperatorLift\BaseMount;
use App\Models\InspectionObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class OperatorLift extends Model
{
    protected $casts = [
        'base_mount' => BaseMount::class,
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

    public function inspectionObject(): MorphOne
    {
        return $this->morphOne(InspectionObject::class, 'inspectable');
    }
}