<?php

namespace App\Livewire\Forms;

use App\Models\InspectionObjects\Crane;
use App\Enums\InspectionObject\Crane\BallastConfiguration;
use App\Enums\InspectionObject\Crane\BaseConfiguration;
use App\Enums\InspectionObject\Crane\BoomConfiguration;
use App\Enums\InspectionObject\Crane\BoomType;
use App\Enums\InspectionObject\Crane\Configuration;
use App\Enums\InspectionObject\Crane\OutriggerType;
use App\Enums\InspectionObject\Crane\Type;
use App\Enums\InspectionObject\Crane\Undercarriage;
use Illuminate\Validation\Rule;
use Livewire\Form;

class CraneForm extends Form
{
    public $exchangeable_parts;

    /**
     * Crane
     */

    public ?Type $type = null;

    public $manufacturer;

    public $model;

    public $serial_number;

    public $asset_number;

    public $year_manufacture;

    public ?Undercarriage $undercarriage = null;

    public ?BaseConfiguration $base_configuration = null;

    public ?OutriggerType $outrigger_type = null;

    /**
     * Base / Undercarriage
     */

    public $base_manufacturer;

    public $base_model;

    public $base_serial_number;

    public $base_asset_number;

    public $base_rail_track_gauge;

    public $base_rail_wheelbase;

    public $base_crane_track_length;

    /**
     * Boom/jib
     */

    public ?BoomType $boom_type = null;

    public bool $boom_is_adjustable = false;

    public bool $boom_is_luffing = false;

    public bool $boom_is_trolley = false;
    
    public $hook_height;

    public $boom_luffing_angle;

    public $boom_length;

    public $boom_parts;

    public function init(Crane $crane): void
    {
        // Crane
        $this->type = $crane->type;
        $this->manufacturer = $crane->manufacturer;
        $this->model = $crane->model;
        $this->serial_number = $crane->serial_number;
        $this->asset_number = $crane->asset_number;
        $this->year_manufacture = $crane->year_manufacture;
        $this->hook_height = $crane->hook_height;
        $this->exchangeable_parts = $crane->exchangeable_parts;
        $this->undercarriage = $crane->undercarriage;
        $this->base_configuration = $crane->base_configuration;
        $this->outrigger_type = $crane->outrigger_type;

        // Base
        $this->base_manufacturer = $crane->base_manufacturer;
        $this->base_model = $crane->base_model;
        $this->base_serial_number = $crane->base_serial_number;
        $this->base_asset_number = $crane->base_asset_number;
        $this->base_rail_track_gauge = $crane->base_rail_track_gauge;
        $this->base_rail_wheelbase = $crane->base_rail_wheelbase;
        $this->base_crane_track_length = $crane->base_crane_track_length;

        // Boom/jib
        $this->boom_type = $crane->boom_type;
        $this->boom_is_adjustable = $crane->boom_is_adjustable ?? false;
        $this->boom_is_luffing = $crane->boom_is_luffing ?? false;
        $this->boom_is_trolley = $crane->boom_is_trolley ?? false;
        $this->boom_length = $crane->boom_length;
        $this->boom_parts = $crane->boom_parts;
        $this->boom_luffing_angle = $crane->boom_luffing_angle;
    }

    public function rules(): array
    {
        return [
            // Crane
            'type' => ['required', Rule::enum(Type::class)],
            'manufacturer' => ['required', 'max:255'],
            'model' => ['nullable', 'max:255'],
            'serial_number' => ['nullable', 'max:255'],
            'asset_number' => ['nullable', 'max:255'],
            'year_manufacture' => ['nullable', 'integer', 'min:1900', 'max:2099'],
            'hook_height' => ['nullable', 'integer'],
            'exchangeable_parts' => ['nullable', 'string'],
            'undercarriage' => ['nullable', Rule::enum(Undercarriage::class)],
            'base_configuration' => ['nullable', Rule::enum(BaseConfiguration::class)],
            'outrigger_type' => ['nullable', Rule::enum(OutriggerType::class)],

            // Boom/jib
            'boom_type' => ['nullable', Rule::enum(BoomType::class)],
            'boom_is_adjustable' => ['nullable', 'boolean'],
            'boom_is_luffing' => ['nullable', 'boolean'],
            'boom_is_trolley' => ['nullable', 'boolean'],
            'boom_length' => ['nullable', 'integer'],
            'boom_luffing_angle' => ['nullable', 'integer'],
            'boom_parts' => ['nullable', 'max:255'],

            // Undercarriage
            'base_manufacturer' => ['nullable', 'max:255'],
            'base_model' => ['nullable', 'max:255'],
            'base_serial_number' => ['nullable', 'max:255'],
            'base_configuration' => ['nullable', Rule::enum(BaseConfiguration::class)],
            'base_rail_track_gauge' => ['nullable', 'integer'],
            'base_rail_wheelbase' => ['nullable', 'integer'],
            'base_crane_track_length' => ['nullable', 'integer'],
        ];
    }
}
