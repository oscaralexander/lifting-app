@use('App\Enums\InspectionObject\Crane\BaseConfiguration')
@use('App\Enums\InspectionObject\Crane\OutriggerType')
@use('App\Enums\InspectionObject\Crane\Undercarriage')

<x-tabs.tab-panel id="base">
    <div class="fields fields--tight">
        <x-form.select
            :default="__('ui.none')"
            :label="__('models/crane.undercarriage.label')"
            model="inspectableForm.undercarriage"
            :options="Undercarriage::options()"
        />
        <div class="grid grid--gap-m" x-cloak x-show="$wire.inspectableForm.undercarriage">
            <div class="grid__col l:grid__col--span-6">
                <x-form.input
                    :label="__('models/crane.base_manufacturer.label')"
                    model="inspectableForm.base_manufacturer"
                />
            </div>
            <div class="grid__col l:grid__col--span-6">
                <x-form.input
                    :label="__('models/crane.base_model.label')"
                    model="inspectableForm.base_model"
                />
            </div>
        </div>
        <div class="grid grid--gap-m" x-cloak x-show="$wire.inspectableForm.undercarriage">
            <div class="grid__col l:grid__col--span-6">
                <x-form.input
                    :label="__('models/crane.base_serial_number.label')"
                    model="inspectableForm.base_serial_number"
                />
            </div>
            <div class="grid__col l:grid__col--span-6">
                <x-form.input
                    :label="__('models/crane.base_asset_number.label')"
                    model="inspectableForm.base_asset_number"
                />
            </div>
        </div>
        <x-form.select
            default="—"
            :label="__('models/crane.base_configuration.label')"
            model="inspectableForm.base_configuration"
            :options="BaseConfiguration::options()"
        />
        <div
            class="grid grid--gap-m"
            x-cloak
            x-show="[
                '{{ BaseConfiguration::CROSS_FRAME->value }}',
                '{{ BaseConfiguration::UNDERCARRIAGE->value }}',
                '{{ BaseConfiguration::RAIL_TRAVELLING->value }}',
            ].includes($wire.inspectableForm.base_configuration)"
        >
            <div class="grid__col l:grid__col--span-6">
                <x-form.input
                    :label="__('models/crane.base_length.label')"
                    model="inspectableForm.base_length"
                    step="0.01"
                    :suffix="__('ui.units.meters')"
                    type="number"
                />
            </div>
            <div class="grid__col l:grid__col--span-6">
                <x-form.input
                    :label="__('models/crane.base_width.label')"
                    model="inspectableForm.base_width"
                    step="0.01"
                    :suffix="__('ui.units.meters')"
                    type="number"
                />
            </div>
        </div>
        <div class="grid grid--gap-m" x-cloak x-show="$wire.inspectableForm.base_configuration === '{{ BaseConfiguration::RAIL_TRAVELLING->value }}'">
            <div class="grid__col l:grid__col--span-6">
                <x-form.input
                    :label="__('models/crane.base_rail_track_gauge.label')"
                    model="inspectableForm.base_rail_track_gauge"
                    :suffix="__('ui.units.meters')"
                    type="number"
                />
            </div>
            <div class="grid__col l:grid__col--span-6">
                <x-form.input
                    :label="__('models/crane.base_rail_wheelbase.label')"
                    model="inspectableForm.base_rail_wheelbase"
                    :suffix="__('ui.units.meters')"
                    type="number"
                />
            </div>
        </div>
        <div class="grid grid--gap-m" x-cloak x-show="$wire.inspectableForm.base_configuration === '{{ BaseConfiguration::RAIL_TRAVELLING->value }}'">
            <div class="grid__col l:grid__col--span-6">
                <x-form.input
                    :label="__('models/crane.base_crane_track_length.label')"
                    model="inspectableForm.base_crane_track_length"
                    :suffix="__('ui.units.meters')"
                    type="number"
                />
            </div>
        </div>
        <x-form.select
            default="—"
            :label="__('models/crane.outrigger_type.label')"
            model="inspectableForm.outrigger_type"
            :options="OutriggerType::options()"
        />
    </div>
</x-tabs.tab-panel>
