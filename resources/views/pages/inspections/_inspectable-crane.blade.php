<div class="u-stack u-stack-gap-xs">
    <h3>@lang('inspections.form.crane.heading_crane')</h3>
    @if ($crane->type)
        <x-data-item :label="__('models/crane.type.label')" :value="$crane->type->label()" />
    @endif
    @if ($crane->central_ballast)
        <x-data-item :label="__('models/crane.central_ballast.label')" :value="$crane->central_ballast" :suffix="__('ui.units.tons')" />
    @endif
    @if ($crane->counter_ballast)
        <x-data-item :label="__('models/crane.counter_ballast.label')" :value="$crane->counter_ballast" :suffix="__('ui.units.tons')" />
    @endif
    @if ($crane->exchangeable_parts)
        <x-data-item :label="__('models/crane.exchangeable_parts.label')" :value="$crane->exchangeable_parts" />
    @endif
</div>
@if ($crane->undercarriage || $crane->base_manufacturer || $crane->base_model || $crane->base_serial_number || $crane->base_asset_number || $crane->base_configuration || $crane->base_length || $crane->base_width || $crane->base_rail_track_gauge || $crane->base_rail_wheelbase || $crane->base_crane_track_length || $crane->outrigger_type)
    <div class="u-stack u-stack-gap-xs">
        <h3>@lang('inspections.form.crane.heading_base')</h3>
        <div>
            @if ($crane->undercarriage)
                <x-data-item :label="__('models/crane.undercarriage.label')" :value="$crane->undercarriage?->label() ?? '—'" />
            @endif
            @if ($crane->base_manufacturer)
                <x-data-item :label="__('models/crane.base_manufacturer.label')" :value="$crane->base_manufacturer" />
            @endif
            @if ($crane->base_model)
                <x-data-item :label="__('models/crane.base_model.label')" :value="$crane->base_model" />
            @endif
            @if ($crane->base_serial_number)
                <x-data-item :label="__('models/crane.base_serial_number.label')" :value="$crane->base_serial_number" />
            @endif
            @if ($crane->base_asset_number)
                <x-data-item :label="__('models/crane.base_asset_number.label')" :value="$crane->base_asset_number" />
            @endif
            @if ($crane->base_configuration)
                <x-data-item :label="__('models/crane.base_configuration.label')" :value="$crane->base_configuration?->label() ?? '—'" />
            @endif
            @if ($crane->base_length)
                <x-data-item :label="__('models/crane.base_length.label')" :value="$crane->base_length" :suffix="__('ui.units.meters')" />
            @endif
            @if ($crane->base_width)
                <x-data-item :label="__('models/crane.base_width.label')" :value="$crane->base_width" :suffix="__('ui.units.meters')" />
            @endif
            @if ($crane->base_rail_track_gauge)
                <x-data-item :label="__('models/crane.base_rail_track_gauge.label')" :value="$crane->base_rail_track_gauge" :suffix="__('ui.units.meters')" />
            @endif
            @if ($crane->base_rail_wheelbase)
                <x-data-item :label="__('models/crane.base_rail_wheelbase.label')" :value="$crane->base_rail_wheelbase" :suffix="__('ui.units.meters')" />
            @endif
            @if ($crane->base_crane_track_length)
                <x-data-item :label="__('models/crane.base_crane_track_length.label')" :value="$crane->base_crane_track_length" :suffix="__('ui.units.meters')" />
            @endif
            @if ($crane->outrigger_type)
                <x-data-item :label="__('models/crane.outrigger_type.label')" :value="$crane->outrigger_type?->label() ?? '—'" />
            @endif
        </div>
    </div>
@endif
@if ($crane->hook_height || !empty($crane->boom_type) || $crane->boom_length || $crane->boom_parts || $crane->boom_is_adjustable || $crane->boom_is_luffing || $crane->boom_is_trolley)
    <div class="u-stack u-stack-gap-xs">
        <h3>@lang('inspections.form.crane.heading_boom')</h3>
        <div>
            @if ($crane->hook_height)
                <x-data-item :label="__('models/crane.hook_height.label')" :value="$crane->hook_height" :suffix="__('ui.units.meters')" />
            @endif
            @if (!empty($crane->boom_type))
                <x-data-item :label="__('models/crane.boom_type.label')" :value="collect($crane->boom_type)->map(fn ($v) => \App\Enums\InspectionObject\Crane\BoomType::from($v)->label())->implode(', ')" />
            @endif
            @if ($crane->boom_is_adjustable)
                <div class="dataItem">
                    <div class="dataItem__label">@lang('models/crane.boom_is_adjustable.label')</div>
                    <div class="dataItem__value"><x-icon icon="check" /></div>
                </div>
            @endif
            @if ($crane->boom_is_luffing)
                <div class="dataItem">
                    <div class="dataItem__label">@lang('models/crane.boom_is_luffing.label')</div>
                    <div class="dataItem__value"><x-icon icon="check" /></div>
                </div>
            @endif
            @if ($crane->boom_is_trolley)
                <div class="dataItem">
                    <div class="dataItem__label">@lang('models/crane.boom_is_trolley.label')</div>
                    <div class="dataItem__value"><x-icon icon="check" /></div>
                </div>
            @endif
            @if ($crane->boom_length)
                <x-data-item :label="__('models/crane.boom_length.label')" :value="$crane->boom_length" :suffix="__('ui.units.meters')" />
            @endif
            @if ($crane->boom_parts)
                <x-data-item :label="__('models/crane.boom_parts.label')" :value="$crane->boom_parts" />
            @endif
            @if ($crane->boom_is_luffing && $crane->boom_luffing_angle)
                <x-data-item :label="__('models/crane.boom_luffing_angle.label')" :value="$crane->boom_luffing_angle" :suffix="__('ui.units.degrees')" />
            @endif
        </div>
    </div>
@endif
