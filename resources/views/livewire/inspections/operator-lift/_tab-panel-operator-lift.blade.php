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
    </div>
</x-tabs.tab-panel>
