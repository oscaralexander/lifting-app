@use('App\Enums\InspectionObject\Type as InspectionObjectType')

<div>
    <x-modal.header>
        @if ($this->type === InspectionObjectType::CRANE)
            @lang('inspections.inspectable.modal.title_crane')
        @else
            @lang('inspections.inspectable.modal.title_operator_lift')
        @endif
    </x-modal.header>
    <x-modal.body>
        <x-form class="u-stack u-stack-gap-xl">
            @if ($this->type === InspectionObjectType::CRANE)
                <x-tabs id="inspectable-modal" activeTab="crane">
                    <x-tabs.tab-list>
                        <x-tabs.tab active id="crane">@lang('inspections.inspectable.modal.crane.tab_crane')</x-tabs.tab>
                        <x-tabs.tab id="base">@lang('inspections.inspectable.modal.crane.tab_base')</x-tabs.tab>
                        <x-tabs.tab id="boom">@lang('inspections.inspectable.modal.crane.tab_boom')</x-tabs.tab>
                    </x-tabs.tab-list>
                    <x-tabs.tab-panels>
                        @include('livewire.inspections.crane._tab-panel-crane')
                        @include('livewire.inspections.crane._tab-panel-base')
                        @include('livewire.inspections.crane._tab-panel-boom')
                    </x-tabs.tab-panels>
                </x-tabs>
            @endif
            @if ($this->type === InspectionObjectType::OPERATOR_LIFT)
                @include('livewire.inspections.operator-lift._tab-panel-operator-lift')
            @endif
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
