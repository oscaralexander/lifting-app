<?php

namespace App\Models\InspectionObjects;

use App\Enums\InspectionObject\Crane\BaseConfiguration;
use App\Enums\InspectionObject\Crane\OutriggerType;
use App\Enums\InspectionObject\Crane\Type;
use App\Enums\InspectionObject\Crane\Undercarriage;
use App\Models\Inspection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Crane extends Model
{
    protected $casts = [
        'base_configuration' => BaseConfiguration::class,
        'base_crane_track_length' => 'integer',
        'base_length' => 'integer',
        'base_rail_track_gauge' => 'integer',
        'base_rail_wheelbase' => 'integer',
        'base_width' => 'integer',
        'boom_is_adjustable' => 'boolean',
        'boom_is_luffing' => 'boolean',
        'boom_is_trolley' => 'boolean',
        'boom_length' => 'integer',
        'boom_luffing_angle' => 'integer',
        'boom_parts' => 'integer',
        'boom_type' => 'array',
        'central_ballast' => 'integer',
        'counter_ballast' => 'integer',
        'hook_height' => 'integer',
        'outrigger_type' => OutriggerType::class,
        'type' => Type::class,
        'undercarriage' => Undercarriage::class,
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
