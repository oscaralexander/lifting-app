@use('App\Models\InspectionObjects\Crane')

<table class="table">
    <thead>
        <tr>
            <th colspan="2" scope="col">@lang('inspections.form.crane.heading_crane')</th>
        </tr>
    </thead>
    <tbody>
        <x-pdf.row :label="__('models/crane.type.label')" :value="$crane->type->label()" />
        <x-pdf.row :label="__('models/crane.central_ballast.label')" :value="$crane->central_ballast" :suffix="__('ui.units.tons')" />
        <x-pdf.row :label="__('models/crane.counter_ballast.label')" :value="$crane->counter_ballast" :suffix="__('ui.units.tons')" />
        <x-pdf.row :label="__('models/crane.exchangeable_parts.label')" :value="$crane->exchangeable_parts" />
    </tbody>
</table>
<!-- Base -->
<table class="table">
    <thead>
        <tr>
            <th colspan="2" scope="col">@lang('inspections.form.crane.heading_base')</th>
        </tr>
    </thead>
    <tbody>
        <x-pdf.row :label="__('models/crane.undercarriage.label')" :value="$crane->undercarriage?->label() ?? ''" />
        <x-pdf.row :label="__('models/crane.base_manufacturer.label')" :value="$crane->base_manufacturer" />
        <x-pdf.row :label="__('models/crane.base_model.label')" :value="$crane->base_model" />
        <x-pdf.row :label="__('models/crane.base_serial_number.label')" :value="$crane->base_serial_number" />
        <x-pdf.row :label="__('models/crane.base_asset_number.label')" :value="$crane->base_asset_number" />
        <x-pdf.row :label="__('models/crane.base_configuration.label')" :value="$crane->base_configuration?->label() ?? ''" />
        <x-pdf.row :label="__('models/crane.base_length.label')" :value="$crane->base_length" :suffix="__('ui.units.meters')" />
        <x-pdf.row :label="__('models/crane.base_width.label')" :value="$crane->base_width" :suffix="__('ui.units.meters')" />
        <x-pdf.row :label="__('models/crane.base_rail_track_gauge.label')" :value="$crane->base_rail_track_gauge" :suffix="__('ui.units.meters')" />
        <x-pdf.row :label="__('models/crane.base_rail_wheelbase.label')" :value="$crane->base_rail_wheelbase" :suffix="__('ui.units.meters')" />
        <x-pdf.row :label="__('models/crane.base_crane_track_length.label')" :value="$crane->base_crane_track_length" :suffix="__('ui.units.meters')" />
        <x-pdf.row :label="__('models/crane.outrigger_type.label')" :value="$crane->outrigger_type?->label() ?? ''" />
    </tbody>
</table>
<!-- Boom -->
<table class="table">
    <thead>
        <tr>
            <th colspan="2" scope="col">@lang('inspections.form.crane.heading_boom')</th>
        </tr>
    </thead>
    <tbody>
        <x-pdf.row :label="__('models/crane.hook_height.label')" :value="$crane->hook_height" :suffix="__('ui.units.meters')" />
        <x-pdf.row :label="__('models/crane.boom_type.label')" :value="collect($crane->boom_type)->map(fn ($v) => \App\Enums\InspectionObject\Crane\BoomType::from($v)->label())->implode(', ')" />
        <x-pdf.row :label="__('models/crane.boom_length.label')" :value="$crane->boom_length" :suffix="__('ui.units.meters')" />
        <x-pdf.row :label="__('models/crane.boom_parts.label')" :value="$crane->boom_parts" />
        <x-pdf.row :label="__('models/crane.boom_is_adjustable.label')" :value="$crane->boom_is_adjustable ? 'Ja' : 'Nee'" />
        <x-pdf.row :label="__('models/crane.boom_is_luffing.label')" :value="$crane->boom_is_luffing ? 'Ja' : 'Nee'" />
        <x-pdf.row :label="__('models/crane.boom_is_trolley.label')" :value="$crane->boom_is_trolley ? 'Ja' : 'Nee'" />
        <x-pdf.row :label="__('models/crane.boom_luffing_angle.label')" :value="$crane->boom_luffing_angle" :suffix="__('ui.units.degrees')" />
    </tbody>
</table>
