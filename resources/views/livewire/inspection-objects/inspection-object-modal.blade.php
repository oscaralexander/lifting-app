@use('App\Enums\InspectionObject\Type as InspectionObjectType')

<div>
    <x-modal.header>@lang('inspection_objects.modal.title_' . ($id ? 'edit' : 'create'))</x-modal.header>
    <x-modal.body>
        <x-form class="u-stack u-stack-gap-xl">
            <div class="grid grid--gap-m">
                <div class="grid__col l:grid__col--span-6">
                    <x-form.input
                        :label="__('models/inspection_object.manufacturer.label')"
                        model="form.manufacturer"
                        required
                    />
                </div>
                <div class="grid__col l:grid__col--span-6">
                    <x-form.input
                        :label="__('models/inspection_object.model.label')"
                        model="form.model"
                    />
                </div>
                <div class="grid__col l:grid__col--span-6">
                    <x-form.input
                        :label="__('models/inspection_object.serial_number.label')"
                        model="form.serial_number"
                    />
                </div>
                <div class="grid__col l:grid__col--span-6">
                    <x-form.input
                        :label="__('models/inspection_object.asset_number.label')"
                        model="form.asset_number"
                    />
                </div>
                <div class="grid__col l:grid__col--span-6">
                    <x-form.input
                        :label="__('models/inspection_object.year_manufacture.label')"
                        model="form.year_manufacture"
                        type="number"
                        min="1900"
                        max="2099"
                    />
                </div>
            </div>
            <div class="actions actions--spaceBetween">
                <div class="actions">
                    <x-btn primary submit>@lang('ui.save')</x-btn>
                    <span>
                        @lang('ui.or')
                        <x-btn text wire:click="$dispatch('closeModal')">@lang('ui.cancel')</x-btn>
                    </span>
                </div>
                @if ($this->inspectionObject->exists)
                    <x-btn danger wire:click="delete()">@lang('ui.delete')</x-btn>
                @endif
            </div>
        </x-form>
    </x-modal.body>
</div>
