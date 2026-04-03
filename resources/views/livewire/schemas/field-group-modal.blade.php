<div>
    <x-modal.header>@lang($id ? 'field_groups.modal.title_edit' : 'field_groups.modal.title_create')</x-modal.header>
    <x-modal.body>
        <x-form class="u-stack u-stack-gap-xl">
            <div class="grid grid--gap-m"">
                <div class="grid__col grid__col--span-6">
                    <x-form.input
                        :label="__('models/field_group.number.label')"
                        maxlength="4"
                        model="number"
                        pattern="[0-9]{4}"
                        :placeholder="__('models/field_group.number.placeholder')"
                        required
                    />
                </div>
                <div class="grid__col">
                    <div class="u-stack u-stack-gap-m">
                        <x-form.textarea
                            :label="__('models/field_group.name.label')"
                            model="name"
                            required
                            rows="1"
                        />
                    </div>
                </div>
            </div>
            <div class="actions">
                <x-btn primary submit>@lang('ui.save')</x-btn>
                <span>
                    @lang('ui.or')
                    <x-btn text wire:click="$dispatch('closeModal')">@lang('ui.cancel')</x-btn>
                </span>
            </div>
        </x-form>
    </x-modal.body>
</div>