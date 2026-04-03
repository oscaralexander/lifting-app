<div>
    <x-modal.header>@lang($id ? 'form_comments.modal.title_edit' : 'form_comments.modal.title_create')</x-modal.header>
    <x-modal.body>
        <x-form class="u-stack u-stack-gap-xl">
            <div class="grid grid--gap-m"">
                <div class="grid__col">
                    <x-form.input
                        :label="__('models/form_comment.comment.label')"
                        model="comment"
                        required
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