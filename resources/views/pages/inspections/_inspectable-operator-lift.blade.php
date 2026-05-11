<div class="u-stack u-stack-gap-xs">
    {{-- <h3>@lang('inspection_objects.show.heading_operator_lift')</h3> --}}
    @if ($operatorLift->base_mount)
        <x-data-item :label="__('models/operator_lift.base_mount.label')" :value="$operatorLift->base_mount->label()" />
    @endif
</div>
