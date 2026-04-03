@use('App\Enums\InspectionObject\Crane\BoomConfiguration')
@use('App\Enums\InspectionObject\Crane\BoomType')

<x-tabs.tab-panel id="boom">
    <div class="grid grid--gap-m">
        <div class="grid__col">
            <x-form.select
                default="—"
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
                :suffix="__('models/crane.boom_length.unit')"
                type="number"
            />
        </div>
        <div class="grid__col l:grid__col--span-6" x-show="$wire.inspectableForm.boom_type === '{{ BoomType::TELESCOPIC->value }}'">
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
                :suffix="__('models/crane.boom_luffing_angle.unit')"
                type="number"
            />
        </div>
    </div>
</x-tabs.tab-panel>
