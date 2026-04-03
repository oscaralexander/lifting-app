<div class="u-stack u-stack-gap-xs">
    <h2>@lang('inspection_objects.show.heading_operator_lift')</h2>
    <dl>
        @if ($operatorLift->manufacturer)
            <dt>@lang('models/operator_lift.manufacturer.label')</dt>
            <dd>{{ $operatorLift->manufacturer }}</dd>
        @endif
        @if ($operatorLift->model)
            <dt>@lang('models/operator_lift.model.label')</dt>
            <dd>{{ $operatorLift->model }}</dd>
        @endif
        @if ($operatorLift->serial_number)
            <dt>@lang('models/operator_lift.serial_number.label')</dt>
            <dd>{{ $operatorLift->serial_number }}</dd>
        @endif
        @if ($operatorLift->asset_number)
            <dt>@lang('models/operator_lift.asset_number.label')</dt>
            <dd>{{ $operatorLift->asset_number }}</dd>
        @endif
        @if ($operatorLift->base_mount)
            <dt>@lang('models/operator_lift.base_mount.label')</dt>
            <dd>{{ $operatorLift->base_mount->label() }}</dd>
        @endif
        @if ($operatorLift->height)
            <dt>@lang('models/operator_lift.height.label')</dt>
            <dd>{{ $operatorLift->height }} m</dd>
        @endif
    </dl>
</div>
