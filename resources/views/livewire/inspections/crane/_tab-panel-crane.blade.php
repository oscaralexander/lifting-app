@use('App\Enums\InspectionObject\Crane\Type')

<x-tabs.tab-panel id="crane">
    <div class="fields fields--tight">
        <x-form.select
            default="—"
            :label="__('models/crane.type.label')"
            wire:model.live="inspectableForm.type"
            :options="Type::options()"
        />
        <div class="grid grid--gap-m">
            <div class="grid__col l:grid__col--span-6">
                <x-form.input
                    :label="__('models/crane.central_ballast.label')"
                    model="inspectableForm.central_ballast"
                    type="number"
                    :suffix="__('ui.units.kilograms')"
                />
            </div>
            <div class="grid__col l:grid__col--span-6">
                <x-form.input
                    :label="__('models/crane.counter_ballast.label')"
                    model="inspectableForm.counter_ballast"
                    type="number"
                    :suffix="__('ui.units.kilograms')"
                />
            </div>
        </div>
        <x-form.textarea
            :label="__('models/crane.exchangeable_parts.label')"
            model="inspectableForm.exchangeable_parts"
        />
    </div>
</x-tabs.tab-panel>
