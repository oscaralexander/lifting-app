<div>
    <x-modal.header>@lang('schemas.modal.title_' . ($id ? 'edit' : 'create'))</x-modal.header>
    <x-modal.body>
        <x-form class="u-stack u-stack-gap-xl">
            <div class="grid grid--gap-m">
                <div class="grid__col">
                    <x-form.input
                        :label="__('models/form.name.label')"
                        model="name"
                        required
                    />
                </div>
                <div class="grid__col">
                    <x-form.select
                        :label="__('models/form.type.label')"
                        model="type"
                        :options="$typeOptions"
                        :default="__('models/form.type.placeholder')"
                        :selected="$type"
                    />
                </div>
                <div class="grid__col">
                    <x-form.textarea
                        :label="__('models/form.description.label')"
                        model="description"
                    />
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
