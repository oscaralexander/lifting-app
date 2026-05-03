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
        'base_crane_track_length' => 'decimal:2',
        'base_length' => 'decimal:2',
        'base_rail_track_gauge' => 'decimal:2',
        'base_rail_wheelbase' => 'decimal:2',
        'base_width' => 'decimal:2',
        'boom_is_adjustable' => 'boolean',
        'boom_is_luffing' => 'boolean',
        'boom_is_trolley' => 'boolean',
        'boom_length' => 'decimal:2',
        'boom_luffing_angle' => 'integer',
        'boom_parts' => 'integer',
        'boom_type' => 'array',
        'central_ballast' => 'decimal:2',
        'counter_ballast' => 'decimal:2',
        'hook_height' => 'decimal:2',
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
