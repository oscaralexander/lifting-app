@use('App\Enums\InspectionObject\Crane\BoomConfiguration')
@use('App\Enums\InspectionObject\Crane\BoomType')

<x-tabs.tab-panel id="boom">
    <div class="grid grid--gap-m">
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('models/crane.hook_height.label')"
                model="inspectableForm.hook_height"
                step="0.01"
                :suffix="__('ui.units.meters')"
                type="number"
            />
        </div>
        <div class="grid__col">
            <x-form.multi-select
                :label="__('models/crane.boom_type.label')"
                model="inspectableForm.boom_type"
                :options="BoomType::options()"
            />
        </div>
        <div class="grid__col">
            <div class="field">
                <div class="field__labelAction">
                    <label class="field__label">
                        @lang('models/crane.boom_configuration.label')
                    </label>
                </div>
                <x-form.lightswitch
                    model="inspectableForm.boom_is_adjustable"
                    :text="__('models/crane.boom_is_adjustable.label')"
                />
                <x-form.lightswitch
                    model="inspectableForm.boom_is_luffing"
                    :text="__('models/crane.boom_is_luffing.label')"
                />
                <x-form.lightswitch
                    model="inspectableForm.boom_is_trolley"
                    :text="__('models/crane.boom_is_trolley.label')"
                />
            </div>
        </div>
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('models/crane.boom_length.label')"
                model="inspectableForm.boom_length"
                step="0.01"
                :suffix="__('ui.units.meters')"
                type="number"
            />
        </div>
        <div class="grid__col l:grid__col--span-6" x-show="Array.isArray($wire.inspectableForm.boom_type) && $wire.inspectableForm.boom_type.includes('{{ BoomType::TELESCOPIC->value }}')">
            <x-form.input
                :label="__('models/crane.boom_parts.label')"
                model="inspectableForm.boom_parts"
                type="number"
            />
        </div>
        <div class="grid__col l:grid__col--span-6" x-show="$wire.inspectableForm.boom_is_luffing">
            <x-form.input
                :label="__('models/crane.boom_luffing_angle.label')"
                model="inspectableForm.boom_luffing_angle"
                :suffix="__('ui.units.degrees')"
                type="number"
            />
        </div>
    </div>
</x-tabs.tab-panel>
