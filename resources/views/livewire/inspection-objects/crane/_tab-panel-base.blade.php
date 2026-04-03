@use('App\Enums\InspectionObject\Crane\Undercarriage')
@use('App\Enums\InspectionObject\Crane\BaseConfiguration')

<x-tabs.tab-panel id="base">
    <div class="grid grid--gap-m">
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
        <div class="grid__col l:grid__col--span-6" x-cloak x-show="$wire.inspectableForm.base_configuration === '{{ BaseConfiguration::RAIL_TRAVELLING->value }}'">
            <x-form.input
                :label="__('models/crane.base_rail_track_gauge.label')"
                model="inspectableForm.base_rail_track_gauge"
            />
        </div>
        <div class="grid__col l:grid__col--span-6" x-cloak x-show="$wire.inspectableForm.base_configuration === '{{ BaseConfiguration::RAIL_TRAVELLING->value }}'">
            <x-form.input
                :label="__('models/crane.base_rail_wheelbase.label')"
                model="inspectableForm.base_rail_wheelbase"
            />
        </div>
    </div>
</x-tabs.tab-panel>
