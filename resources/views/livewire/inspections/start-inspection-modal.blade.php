<div>
    <x-modal.header>@lang($inspectionHash ? 'inspections.start.modal.title_relink' : 'inspections.start.modal.title')</x-modal.header>
    <x-modal.body>
        <x-form class="u-stack u-stack-gap-xl">
            <div class="grid grid--gap-l">
                <div class="grid__col">
                    <x-form.select
                        default="—"
                        :label="__('inspections.start.modal.client')"
                        wire:model.live="clientId"
                        :options="$this->clients"
                        :selected="$clientId"
                        required
                    />
                </div>
                @island(lazy: true, always: true)
                    @placeholder
                        <div class="grid__col">
                            <div class="field">
                                <label class="field__label">@lang('inspections.start.modal.work_order')</label>
                                <div class="selectLoader">
                                    <x-icon icon="loader-circle" />
                                    @lang('inspections.start.modal.loading_work_orders')
                                </div>
                            </div>
                        </div>
                    @endplaceholder

                    <div class="grid__col">
                        <div wire:loading.remove.block wire:target="clientId">
                            <x-form.select
                                default="—"
                                :disabled="! $clientId"
                                :label="__('inspections.start.modal.work_order')"
                                wire:model.live="workOrderId"
                                :options="$this->workOrderOptions"
                                :selected="$workOrderId"
                                required
                            />
                        </div>
                        <div wire:loading.block wire:target="clientId">
                            <div class="field">
                                <label class="field__label field__label--required">@lang('inspections.start.modal.work_order')</label>
                                <div class="selectLoader">
                                    <x-icon icon="loader-circle" />
                                    @lang('inspections.start.modal.loading_work_orders')
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($this->selectedWorkOrder)
                        <div class="grid__col">
                            <x-data-item :label="__('inspections.start.modal.summary_status')" :value="($this->selectedWorkOrder['status'] ?? null) ?: '—'" />
                            <x-data-item :label="__('inspections.start.modal.summary_reference')" :value="($this->selectedWorkOrder['Reference'] ?? null) ?: '—'" />
                            <x-data-item :label="__('inspections.start.modal.summary_creation_date')" :value="($this->selectedWorkOrder['CreationDate'] ?? null) ?: '—'" />
                        </div>
                    @endif
                @endisland
            </div>
            <div class="actions">
                <x-btn primary submit>@lang($inspectionHash ? 'inspections.start.modal.btn_relink' : 'inspections.start.modal.btn_start')</x-btn>
                <span>
                    @lang('ui.or')
                    <x-btn text wire:click="$dispatch('closeModal')">@lang('ui.cancel')</x-btn>
                </span>
            </div>
        </x-form>
    </x-modal.body>
</div>
