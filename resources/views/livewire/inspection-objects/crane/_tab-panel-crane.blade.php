@use('App\Enums\InspectionObject\Crane\BaseConfiguration')
@use('App\Enums\InspectionObject\Crane\OutriggerType')
@use('App\Enums\InspectionObject\Crane\Type')
@use('App\Enums\InspectionObject\Crane\Undercarriage')

<x-tabs.tab-panel id="inspection-object">
    <div class="grid grid--gap-m">
        <div class="grid__col">
            <x-form.select
                default="—"
                :label="__('models/crane.type.label')"
                wire:model.live="inspectableForm.type"
                :options="Type::options()"
            />
        </div>
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('models/crane.manufacturer.label')"
                model="inspectableForm.manufacturer"
            />
        </div>
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('models/crane.model.label')"
                model="inspectableForm.model"
            />
        </div>
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('models/crane.serial_number.label')"
                model="inspectableForm.serial_number"
            />
        </div>
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('models/crane.asset_number.label')"
                model="inspectableForm.asset_number"
            />
        </div>
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('models/crane.year_manufacture.label')"
                model="inspectableForm.year_manufacture"
                type="number"
                min="1900"
                max="2099"
            />
        </div>
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('models/crane.hook_height.label')"
                model="inspectableForm.hook_height"
                :suffix="__('models/crane.hook_height.unit')"
                type="number"
            />
        </div>
        <div class="grid__col">
            <x-form.textarea
                :label="__('models/crane.exchangeable_parts.label')"
                model="inspectableForm.exchangeable_parts"
            />
        </div>
        <div class="grid__col">
            <x-form.select
                default="—"
                :label="__('models/crane.undercarriage.label')"
                model="inspectableForm.undercarriage"
                :options="Undercarriage::options()"
            />
        </div>
        <div class="grid__col">
            <x-form.select
                default="—"
                :label="__('models/crane.base_configuration.label')"
                model="inspectableForm.base_configuration"
                :options="BaseConfiguration::options()"
            />
        </div>
        <div class="grid__col">
            <x-form.select
                default="—"
                :label="__('models/crane.outrigger_type.label')"
                model="inspectableForm.outrigger_type"
                :options="OutriggerType::options()"
            />
        </div>
    </div>
</x-tabs.tab-panel>
