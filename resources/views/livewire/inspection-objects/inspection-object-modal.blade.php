@use('App\Enums\InspectionObject\Type as InspectionObjectType')
@use('App\Enums\InspectionObject\Configuration as InspectionObjectConfiguration')

<div>
    <x-modal.header>@lang('inspection_objects.modal.title_' . ($id ? 'edit' : 'create'))</x-modal.header>
    <x-modal.body>
        <x-form class="u-stack u-stack-gap-xl">
            <x-tabs id="inspection-object-modal">
                <x-tabs.tab-list>
                    <x-tabs.tab active id="general">@lang('inspection_objects.form.tab_general')</x-tabs.tab>
                    @if ($this->type === InspectionObjectType::CRANE)
                        <x-tabs.tab id="inspection-object">@lang('inspection_objects.form.tab_crane')</x-tabs.tab>
                        <x-tabs.tab id="base">@lang('inspection_objects.form.tab_base')</x-tabs.tab>
                        <x-tabs.tab id="boom">@lang('inspection_objects.form.tab_boom')</x-tabs.tab>
                    @endif
                    @if ($this->type === InspectionObjectType::OPERATOR_LIFT)
                        <x-tabs.tab id="lift">@lang('inspection_objects.form.tab_lift')</x-tabs.tab>
                    @endif
                </x-tabs.tab-list>
                <x-tabs.tab-panels>
                    @include('livewire.inspection-objects._tab-panel-general')
                    @if ($this->type === InspectionObjectType::CRANE)
                        @include('livewire.inspection-objects.crane._tab-panel-crane')
                        @include('livewire.inspection-objects.crane._tab-panel-base')
                        @include('livewire.inspection-objects.crane._tab-panel-boom')
                        {{-- @include('livewire.inspection-objects.crane._tab-panel-rail')
                        @include('livewire.inspection-objects.crane._tab-panel-lift')
                        @include('livewire.inspection-objects.crane._tab-panel-ballast') --}}
                    @endif
                    {{--
                    @include('livewire.inspection-objects._tab-panel-crane')
                    @include('livewire.inspection-objects._tab-panel-undercarriage')
                    @include('livewire.inspection-objects._tab-panel-rail')
                    @include('livewire.inspection-objects._tab-panel-boom')
                    --}}
                    @if ($this->type === InspectionObjectType::OPERATOR_LIFT)
                        @include('livewire.inspection-objects.operator-lift._tab-panel-operator-lift')
                    @endif
                </x-tabs.tab-panels>
            </x-tabs>
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
