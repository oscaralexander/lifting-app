<?php

namespace App\Models\InspectionObjects;

use App\Enums\InspectionObject\Crane\BallastConfiguration;
use App\Enums\InspectionObject\Crane\BaseConfiguration;
use App\Enums\InspectionObject\Crane\BoomConfiguration;
use App\Enums\InspectionObject\Crane\BoomType;
use App\Enums\InspectionObject\Crane\OutriggerType;
use App\Enums\InspectionObject\Crane\Type;
use App\Enums\InspectionObject\Crane\Undercarriage;
use App\Models\InspectionObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Crane extends Model
{
    protected $casts = [
        'ballast_configuration' => BallastConfiguration::class,
        'base_configuration' => BaseConfiguration::class,
        'boom_configuration' => BoomConfiguration::class,
        'boom_length' => 'integer',
        'boom_type' => BoomType::class,
        'central_ballast' => 'integer',
        'undercarriage' => Undercarriage::class,
        'base_configuration' => BaseConfiguration::class,
        'counterweight_ballast' => 'integer',
        'crane_track_length' => 'integer',
        'hook_height' => 'integer',
        'outrigger_type' => OutriggerType::class,
        'rail_track_gauge' => 'integer',
        'rail_wheelbase' => 'integer',
        'type' => Type::class,
        'year_manufacture' => 'integer',
        'undercarriage' => Undercarriage::class,
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