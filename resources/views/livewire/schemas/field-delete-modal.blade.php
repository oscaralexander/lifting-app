<div>
    <x-modal.header>@lang('fields.delete_modal.title')</x-modal.header>
    <x-modal.body>
        <x-form class="u-stack u-stack-gap-xl">
            <div class="formatted">
                <p>
                    @lang('fields.delete_modal.confirm', [
                        'count' => $this->formCount,
                        'forms' => trans_choice('fields.delete_modal.confirm_forms', $this->formCount)
                    ])
                </p>
            </div>
            <div class="actions">
                <x-btn danger submit>@lang('ui.delete')</x-btn>
                <span>
                    @lang('ui.or')
                    <x-btn text wire:click="$dispatch('closeModal')">@lang('ui.cancel')</x-btn>
                </span>
            </div>
        </x-form>
    </x-modal.body>
</div>