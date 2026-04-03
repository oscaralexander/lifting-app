@use('App\Enums\InspectionObject\OperatorLift\BaseMount')

<x-tabs.tab-panel id="lift">
    <div class="grid grid--gap-m">
        <div class="grid__col">
            <x-form.select
                default="—"
                :label="__('models/operator_lift.base_mount.label')"
                model="inspectableForm.base_mount"
                :options="BaseMount::options()"
            />
        </div>
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('models/operator_lift.manufacturer.label')"
                model="inspectableForm.manufacturer"
            />
        </div>
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('models/operator_lift.model.label')"
                model="inspectableForm.model"
            />
        </div>
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('models/operator_lift.serial_number.label')"
                model="inspectableForm.serial_number"
            />
        </div>
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('models/operator_lift.asset_number.label')"
                model="inspectableForm.asset_number"
            />
        </div>
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('models/operator_lift.height.label')"
                model="inspectableForm.height"
                suffix="m"
                type="number"
            />
        </div>
    </div>
</x-tabs.tab-panel>
