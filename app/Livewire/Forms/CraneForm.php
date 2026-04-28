<?php

namespace App\Livewire\Forms;

use App\Enums\InspectionObject\Crane\BaseConfiguration;
use App\Enums\InspectionObject\Crane\BoomType;
use App\Enums\InspectionObject\Crane\OutriggerType;
use App\Enums\InspectionObject\Crane\Type;
use App\Enums\InspectionObject\Crane\Undercarriage;
use App\Models\InspectionObjects\Crane;
use Illuminate\Validation\Rule;
use Livewire\Form;

class CraneForm extends Form
{
    public $exchangeable_parts;

    /**
     * Crane
     */
    public ?Type $type = null;

    public ?Undercarriage $undercarriage = null;

    public ?BaseConfiguration $base_configuration = null;

    public ?OutriggerType $outrigger_type = null;

    public $hook_height;

    public $central_ballast;

    public $counter_ballast;

    /**
     * Base / Undercarriage
     */
    public $base_manufacturer;

    public $base_model;

    public $base_serial_number;

    public $base_asset_number;

    public $base_length;

    public $base_width;

    public $base_rail_track_gauge;

    public $base_rail_wheelbase;

    public $base_crane_track_length;

    /**
     * Boom/jib
     */
    public array $boom_type = [];

    public bool $boom_is_adjustable = false;

    public bool $boom_is_luffing = false;

    public bool $boom_is_trolley = false;

    public $boom_luffing_angle;

    public $boom_length;

    public $boom_parts;

    public function init(Crane $crane): void
    {
        // Crane
        $this->type = $crane->type;
        $this->hook_height = $crane->hook_height;
        $this->central_ballast = $crane->central_ballast;
        $this->counter_ballast = $crane->counter_ballast;
        $this->exchangeable_parts = $crane->exchangeable_parts;
        $this->undercarriage = $crane->undercarriage;
        $this->base_configuration = $crane->base_configuration;
        $this->outrigger_type = $crane->outrigger_type;

        // Base
        $this->base_manufacturer = $crane->base_manufacturer;
        $this->base_model = $crane->base_model;
        $this->base_serial_number = $crane->base_serial_number;
        $this->base_asset_number = $crane->base_asset_number;
        $this->base_length = $crane->base_length;
        $this->base_width = $crane->base_width;
        $this->base_rail_track_gauge = $crane->base_rail_track_gauge;
        $this->base_rail_wheelbase = $crane->base_rail_wheelbase;
        $this->base_crane_track_length = $crane->base_crane_track_length;

        // Boom/jib
        $this->boom_type = $crane->boom_type ?? [];
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
            'hook_height' => ['nullable', 'integer'],
            'central_ballast' => ['nullable', 'integer'],
            'counter_ballast' => ['nullable', 'integer'],
            'exchangeable_parts' => ['nullable', 'string'],
            'undercarriage' => ['nullable', Rule::enum(Undercarriage::class)],
            'base_configuration' => ['nullable', Rule::enum(BaseConfiguration::class)],
            'outrigger_type' => ['nullable', Rule::enum(OutriggerType::class)],

            // Boom/jib
            'boom_type' => ['nullable', 'array'],
            'boom_type.*' => [Rule::enum(BoomType::class)],
            'boom_is_adjustable' => ['nullable', 'boolean'],
            'boom_is_luffing' => ['nullable', 'boolean'],
            'boom_is_trolley' => ['nullable', 'boolean'],
            'boom_length' => ['nullable', 'integer'],
            'boom_luffing_angle' => ['nullable', 'integer'],
            'boom_parts' => ['nullable', 'integer'],

            // Undercarriage
            'base_manufacturer' => ['nullable', 'max:255'],
            'base_model' => ['nullable', 'max:255'],
            'base_serial_number' => ['nullable', 'max:255'],
            'base_asset_number' => ['nullable', 'max:255'],
            'base_length' => ['nullable', 'integer'],
            'base_width' => ['nullable', 'integer'],
            'base_rail_track_gauge' => ['nullable', 'integer'],
            'base_rail_wheelbase' => ['nullable', 'integer'],
            'base_crane_track_length' => ['nullable', 'integer'],
        ];
    }
}
