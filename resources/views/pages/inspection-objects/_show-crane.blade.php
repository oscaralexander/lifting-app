<div class="u-stack u-stack-gap-xs">
    <h3>@lang('inspection_objects.show.heading_crane')</h3>
    @if ($crane->type)
        <div class="dataItem">
            <div class="dataItem__label">@lang('models/crane.type.label')</div>
            <div class="dataItem__value">{{ $crane->type->label() }}</div>
        </div>
    @endif
    @if ($crane->manufacturer)
        <div class="dataItem">
            <div class="dataItem__label">@lang('models/crane.manufacturer.label')</div>
            <div class="dataItem__value">{{ $crane->manufacturer }}</div>
        </div>
    @endif
    @if ($crane->model)
        <div class="dataItem">
            <div class="dataItem__label">@lang('models/crane.model.label')</div>
            <div class="dataItem__value">{{ $crane->model }}</div>
        </div>
    @endif
    @if ($crane->serial_number)
        <div class="dataItem">
            <div class="dataItem__label">@lang('models/crane.serial_number.label')</div>
            <div class="dataItem__value">{{ $crane->serial_number }}</div>
        </div>
    @endif
    @if ($crane->asset_number)
        <div class="dataItem">
            <div class="dataItem__label">@lang('models/crane.asset_number.label')</div>
            <div class="dataItem__value">{{ $crane->asset_number }}</div>
        </div>
    @endif
    @if ($crane->year_manufacture)
        <div class="dataItem">
            <div class="dataItem__label">@lang('models/crane.year_manufacture.label')</div>
            <div class="dataItem__value">{{ $crane->year_manufacture }}</div>
        </div>
    @endif
    @if ($crane->hook_height)
        <div class="dataItem">
            <div class="dataItem__label">@lang('models/crane.hook_height.label')</div>
            <div class="dataItem__value">{{ $crane->hook_height }} @lang('models/crane.hook_height.unit')</div>
        </div>
    @endif
    @if ($crane->exchangeable_parts)
        <div class="dataItem">
            <div class="dataItem__label">@lang('models/crane.exchangeable_parts.label')</div>
            <div class="dataItem__value">{{ $crane->exchangeable_parts }}</div>
        </div>
    @endif
    @if ($crane->undercarriage)
        <div class="dataItem">
            <div class="dataItem__label">@lang('models/crane.undercarriage.label')</div>
            <div class="dataItem__value">{{ $crane->undercarriage?->label() ?? '—' }}</div>
        </div>
    @endif
    @if ($crane->base_configuration)
        <div class="dataItem">
            <div class="dataItem__label">@lang('models/crane.base_configuration.label')</div>
            <div class="dataItem__value">{{ $crane->base_configuration?->label() ?? '—' }}</div>
        </div>
    @endif
    @if ($crane->outrigger_type)
        <div class="dataItem">
            <div class="dataItem__label">@lang('models/crane.outrigger_type.label')</div>
            <div class="dataItem__value">{{ $crane->outrigger_type?->label() ?? '—' }}</div>
        </div>
    @endif
</div>
@if ($crane->base_manufacturer || $crane->base_model || $crane->base_serial_number || $crane->base_asset_number || $crane->base_rail_track_gauge || $crane->base_rail_wheelbase || $crane->base_crane_track_length)
    <div class="u-stack u-stack-gap-xs">
        <h3>@lang('inspection_objects.show.heading_undercarriage')</h3>
        <div>
            @if ($crane->base_manufacturer)
                <div class="dataItem">
                    <div class="dataItem__label">@lang('models/crane.base_manufacturer.label')</div>
                    <div class="dataItem__value">{{ $crane->base_manufacturer }}</div>
                </div>
            @endif
            @if ($crane->base_model)
                <div class="dataItem">
                    <div class="dataItem__label">@lang('models/crane.base_model.label')</div>
                    <div class="dataItem__value">{{ $crane->base_model }}</div>
                </div>
            @endif
            @if ($crane->base_serial_number)
                <div class="dataItem">
                    <div class="dataItem__label">@lang('models/crane.base_serial_number.label')</div>
                    <div class="dataItem__value">{{ $crane->base_serial_number }}</div>
                </div>
            @endif
            @if ($crane->base_asset_number)
                <div class="dataItem">
                    <div class="dataItem__label">@lang('models/crane.base_asset_number.label')</div>
                    <div class="dataItem__value">{{ $crane->base_asset_number }}</div>
                </div>
            @endif
            @if ($crane->base_rail_track_gauge)
                <div class="dataItem">
                    <div class="dataItem__label">@lang('models/crane.base_rail_track_gauge.label')</div>
                    <div class="dataItem__value">{{ $crane->base_rail_track_gauge }}</div>
                </div>
            @endif
            @if ($crane->base_rail_wheelbase)
                <div class="dataItem">
                    <div class="dataItem__label">@lang('models/crane.base_rail_wheelbase.label')</div>
                    <div class="dataItem__value">{{ $crane->base_rail_wheelbase }}</div>
                </div>
            @endif
            @if ($crane->base_crane_track_length)
                <div class="dataItem">
                    <div class="dataItem__label">@lang('models/crane.base_crane_track_length.label')</div>
                    <div class="dataItem__value">{{ $crane->base_crane_track_length }}</div>
                </div>
            @endif
        </div>
    </div>
@endif
@if ($crane->boom_type || $crane->boom_length || $crane->boom_parts || $crane->boom_is_adjustable || $crane->boom_is_luffing || $crane->boom_is_trolley)
    <div class="u-stack u-stack-gap-xs">
        <h3>@lang('inspection_objects.show.heading_boom')</h3>
        <div>
            @if ($crane->boom_type)
                <div class="dataItem">
                    <div class="dataItem__label">@lang('models/crane.boom_type.label')</div>
                    <div class="dataItem__value">{{ $crane->boom_type?->label() ?? '—' }}</div>
                </div>
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
                <div class="dataItem">
                    <div class="dataItem__label">@lang('models/crane.boom_length.label')</div>
                    <div class="dataItem__value">{{ $crane->boom_length }}@lang('models/crane.boom_length.unit')</div>
                </div>
            @endif
            @if ($crane->boom_parts)
                <div class="dataItem">
                    <div class="dataItem__label">@lang('models/crane.boom_parts.label')</div>
                    <div class="dataItem__value">{{ $crane->boom_parts }}</div>
                </div>
            @endif
            @if ($crane->boom_is_luffing && $crane->boom_luffing_angle)
                <div class="dataItem">
                    <div class="dataItem__label">@lang('models/crane.boom_luffing_angle.label')</div>
                    <div class="dataItem__value">{{ $crane->boom_luffing_angle }}@lang('models/crane.boom_luffing_angle.unit')</div>
                </div>
            @endif
        </div>
    </div>
@endif
