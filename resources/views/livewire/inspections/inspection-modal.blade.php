<div>
    <x-modal.header>@lang('inspections.project.modal.title')</x-modal.header>
    <x-modal.body>
        <x-form class="u-stack u-stack-gap-xl">
            <div class="grid grid--gap-l">
                <div class="grid__col">
                    <x-form.select
                        default="—"
                        :label="__('models/inspection.type.label')"
                        wire:model="form.type"
                        :options="$this->types"
                        :selected="$form->type"
                        required
                    />
                </div>
                <div class="grid__col">
                    <x-form.select
                        default="—"
                        :label="__('models/inspection.client_id.label')"
                        wire:model="form.clientId"
                        :options="$this->clients"
                        :selected="$form->clientId"
                        required
                    >
                        <x-slot:field-action>
                            <x-btn icon="plus" small text x-on:click="$dispatch('openModal', { component: 'clients.client-modal', arguments: { id: null } })">Nieuwe klant</x-btn>
                        </x-slot:field-action>
                    </x-form.select>
                </div>
                <div class="grid__col">
                    <x-form.input
                        :label="__('models/inspection.project_name.label')"
                        wire:model="form.projectName"
                    />
                </div>
                <div class="grid__col">
                    <x-form.input
                        :label="__('models/inspection.project_address.label')"
                        wire:model="form.projectAddress"
                    />
                </div>
                <div class="grid__col l:grid__col--span-4">
                    <x-form.input
                        :label="__('models/inspection.project_postal_code.label')"
                        wire:model="form.projectPostalCode"
                    />
                </div>
                <div class="grid__col l:grid__col--span-8">
                    <x-form.input
                        :label="__('models/inspection.project_city.label')"
                        wire:model="form.projectCity"
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
