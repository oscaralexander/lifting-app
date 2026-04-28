<?php

namespace App\Models\InspectionObjects;

use App\Enums\InspectionObject\OperatorLift\BaseMount;
use App\Models\Inspection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OperatorLift extends Model
{
    protected $casts = [
        'base_mount' => BaseMount::class,
    ];

    protected $guarded = ['id'];

    /**
     * Relationships
     */
    public function inspections(): MorphMany
    {
        return $this->morphMany(Inspection::class, 'inspectable');
    }
}
