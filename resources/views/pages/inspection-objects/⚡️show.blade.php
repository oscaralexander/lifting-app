<?php

use App\Constants\Event;
use App\Enums\InspectionObject\Type;
use App\Models\Form;
use App\Models\InspectionObject;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public int $inspectionObjectId;

    public function mount(InspectionObject $inspectionObject): void
    {
        $this->inspectionObjectId = $inspectionObject->id;
    }

    #[Computed]
    public function inspectionObject(): InspectionObject
    {
        return InspectionObject::with('client', 'inspectable', 'inspections')->findOrFail($this->inspectionObjectId);
    }

    #[On(Event::REFRESH)]
    public function refresh(): void
    {
        unset($this->inspectionObject);
    }

    #[Computed]
    public function forms(): Collection
    {
        return Form::where('type', $this->inspectionObject->type->value)->get();
    }

    public function render()
    {
        return $this->view()
            ->title($this->inspectionObject->inspectable?->name ?? __('inspection_objects.show.title'));
    }
}
?>

<div>
    <x-header
        :title="$this->inspectionObject->inspectable?->name ?? __('inspection_objects.show.unnamed')"
        :intro="$this->inspectionObject->type->label()"
        :path="[
            __('inspection_objects.index.title') => route('inspection-objects'),
        ]"
    >
        <x-slot:actions>
            <x-popout icon="plus" :label="__('inspection_objects.show.btn_create_inspection')" primary>
                @foreach ($this->forms as $form)
                    <x-popout.item
                        :href="route('inspections.form', ['inspectionObjectId' => $this->inspectionObject->id, 'formSlug' => $form->slug])"
                        :label="$form->name"
                    />
                @endforeach
            </x-popout>
        </x-slot:actions>
    </x-header>
    <div class="u-stack u-stack-gap-l">
        <div class="grid grid--gap-xl">
            @if ($this->inspectionObject->inspectable)
                <div class="grid__col l:grid__col--span-6">
                    <div class="sticky">
                        <div class="u-stack u-stack-gap-xl">
                            <header class="u-flex u-flex-align-center u-flex-justify-between">
                                <h2>@lang('inspection_objects.show.heading_object')</h2>
                                <x-btn
                                    icon="pencil"
                                    small
                                    wire:click.prevent="$dispatch('openModal', { component: 'inspection-objects.inspection-object-modal', arguments: { id: {{ $this->inspectionObject->id }}, type: '{{ $this->inspectionObject->type->value }}' } })"
                                >@lang('ui.edit')</x-btn>
                            </header>
                            <div>
                                <div class="dataItem">
                                    <div class="dataItem__label">{{ __('inspection_objects.show.client') }}</div>
                                    <div class="dataItem__value">
                                        {{ $this->inspectionObject->client->name }}<br>
                                        {{ $this->inspectionObject->client->address }}<br>
                                        {{ collect([$this->inspectionObject->client->postal_code, $this->inspectionObject->client->city])->filter()->implode(' ') }}<br>
                                        {{ $this->inspectionObject->client->country->label() }}
                                    </div>
                                </div>
                                <div class="dataItem">
                                    <div class="dataItem__label">{{ __('inspection_objects.show.address') }}</div>
                                    <div class="dataItem__value">
                                        {{ $this->inspectionObject->address }}<br>
                                        {{ collect([$this->inspectionObject->postal_code, $this->inspectionObject->city])->filter()->implode(' ') }}<br>
                                        {{ $this->inspectionObject->country->label() }}
                                    </div>
                                </div>
                            </div>
                            @if ($this->inspectionObject->type === Type::CRANE)
                                @include('pages.inspection-objects._show-crane', ['crane' => $this->inspectionObject->inspectable])
                            @elseif ($this->inspectionObject->type === Type::OPERATOR_LIFT)
                                @include('pages.inspection-objects._show-operator-lift', ['operatorLift' => $this->inspectionObject->inspectable])
                            @endif
                        </div>
                    </div>
                </div>
                <div class="grid__col l:grid__col--span-6" style="position: relative;">
                    <div class="sticky">
                        @include('pages.inspection-objects._inspections')
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
