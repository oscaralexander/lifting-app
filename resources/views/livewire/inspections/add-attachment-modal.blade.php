<div>
    <x-modal.header>@lang('inspections.attachment.modal.title')</x-modal.header>
    <x-modal.body>
        <x-form class="u-stack u-stack-gap-xl">
            <div class="grid grid--gap-l">
                <div class="grid__col">
                    <x-form.select
                        default="—"
                        :description="empty($this->attachmentOptions) ? __('inspections.attachment.modal.no_attachments') : null"
                        :disabledOptions="$this->linkedAttachmentIds"
                        :label="__('inspections.attachment.modal.attachment')"
                        wire:model="inspectionObjectId"
                        :options="$this->attachmentOptions"
                        :selected="$inspectionObjectId"
                        required
                    />
                </div>
                <div class="grid__col">
                    <x-form.select
                        default="—"
                        :description="empty($this->formOptions) ? __('inspections.attachment.modal.no_forms') : null"
                        :label="__('inspections.attachment.modal.form')"
                        wire:model="formId"
                        :options="$this->formOptions"
                        :selected="$formId"
                        required
                    />
                </div>
            </div>
            <div class="actions">
                <x-btn primary submit>@lang('inspections.attachment.modal.btn_add')</x-btn>
                <span>
                    @lang('ui.or')
                    <x-btn text wire:click="$dispatch('closeModal')">@lang('ui.cancel')</x-btn>
                </span>
            </div>
        </x-form>
    </x-modal.body>
</div>
