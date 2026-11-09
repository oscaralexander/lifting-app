<div>
    @php
        $relinkKey = $this->isAttachment ? 'relink_parent' : 'relink';
    @endphp
    <x-modal.header>@lang($inspectionHash ? 'inspections.start.modal.title_'.$relinkKey : 'inspections.start.modal.title')</x-modal.header>
    <x-modal.body>
        <x-form class="u-stack u-stack-gap-xl">
            <div class="grid grid--gap-l">
                @if ($this->isAttachment)
                    <div class="grid__col">
                        <x-form.select
                            default="—"
                            :description="__('inspections.start.modal.parent_inspection_description')"
                            :label="__('inspections.start.modal.parent_inspection')"
                            wire:model="parentInspectionId"
                            :options="$this->parentInspectionOptions"
                            :selected="$parentInspectionId"
                            required
                        />
                    </div>
                @else
                    <div class="grid__col">
                        <x-form.select
                            default="—"
                            :label="__('inspections.start.modal.type')"
                            wire:model="type"
                            :options="$this->typeOptions"
                            :selected="$type"
                            required
                        />
                    </div>
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
                @endif
                @island(lazy: true, always: true)
                    @placeholder
                        @unless ($this->isAttachment)
                        <div class="grid__col">
                            <div class="field">
                                <label class="field__label">@lang('inspections.start.modal.work_order')</label>
                                <div class="selectLoader">
                                    <x-icon icon="loader-circle" />
                                    @lang('inspections.start.modal.loading_work_orders')
                                </div>
                            </div>
                        </div>
                        @endunless
                    @endplaceholder
                    @unless ($this->isAttachment)
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
                        @php
                            $workDate = $this->selectedWorkOrder['WorkDate'] ?? null;
                            $workDate = !empty($workDate) ? \Carbon\Carbon::createFromFormat('d-m-Y', $workDate)->translatedFormat('j F Y') : '—';
                        @endphp
                        <div class="grid__col">
                            <x-data-item :label="__('inspections.start.modal.summary_status')" :value="($this->selectedWorkOrder['status'] ?? null) ?: '—'" />
                            <x-data-item :label="__('inspections.start.modal.summary_reference')" :value="($this->selectedWorkOrder['Reference'] ?? null) ?: '—'" />
                            <x-data-item :label="__('inspections.start.modal.summary_work_date')" :value="$workDate" />
                        </div>
                    @endif
                    @endunless
                @endisland
            </div>
            <div class="actions">
                <x-btn primary submit>@lang($inspectionHash ? 'inspections.start.modal.btn_'.$relinkKey : 'inspections.start.modal.btn_start')</x-btn>
                <span>
                    @lang('ui.or')
                    <x-btn text wire:click="$dispatch('closeModal')">@lang('ui.cancel')</x-btn>
                </span>
            </div>
        </x-form>
    </x-modal.body>
</div>
