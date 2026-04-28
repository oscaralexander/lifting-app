<div class="u-stack u-stack-gap-xs">
    <h2>@lang('inspection_objects.show.heading_operator_lift')</h2>
    <dl>
        @if ($operatorLift->base_mount)
            <dt>@lang('models/operator_lift.base_mount.label')</dt>
            <dd>{{ $operatorLift->base_mount->label() }}</dd>
        @endif
    </dl>
</div>
