<?php

use App\Constants\Event;
use App\Constants\SessionKey;
use App\Enums\FieldType;
use App\Enums\InspectionObject\Type as InspectionObjectType;
use App\Lib\FormItems;
use App\Livewire\Forms\InspectionSubmissionForm;
use App\Models\Client;
use App\Models\Inspection;
use App\Models\InspectionObject;
use App\Models\InspectionObjects\Crane;
use App\Models\InspectionObjects\OperatorLift;
use App\Models\Form;
use App\Services\OutsmartService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

new class extends Component
{
    use WithFileUploads;

    public InspectionSubmissionForm $submissionForm;

    #[Locked]
    public ?string $inspectionHash = null;

    #[Locked]
    public int $inspectionObjectId;

    #[Locked]
    public string $formSlug;

    #[Computed]
    public function client(): ?Client
    {
        if ($this->form->client_id) {
            return Client::find($this->form->client_id);
        }

        return null;
    }

    public function deleteImage(string $fieldId, string $image): void
    {
        if (!Storage::disk('local')->exists($image)) {
            abort(404);
        }

        $this->submissionForm->deleteImage($fieldId, $image);
        Storage::disk('local')->delete($image);
    }

    public function deleteInspectionImage(string $image): void
    {
        $this->submissionForm->deleteInspectionImage($image);

        if (Storage::disk('public')->exists($image)) {
            Storage::disk('public')->delete($image);
        }
    }

    public function downloadImage(string $fieldId, string $image): BinaryFileResponse
    {
        if (!Storage::disk('local')->exists($image)) {
            abort(404);
        }

        return response()->download(Storage::disk('local')->path($image));
    }

    public function downloadInspectionImage(string $image): BinaryFileResponse
    {
        if (!Storage::disk('public')->exists($image)) {
            abort(404);
        }

        return response()->download(Storage::disk('public')->path($image));
    }

    #[Computed]
    public function form(): Form
    {
        return Form::where('slug', $this->formSlug)->firstOrFail();
    }

    #[Computed]
    public function inspection(): Inspection
    {
        return Inspection::query()
            ->with('client', 'form', 'inspectable', 'inspectionObject')
            ->where('hash', $this->inspectionHash)
            ->firstOrNew([
                'form_id' => $this->form->id,
                'inspection_object_id' => $this->inspectionObjectId,
            ]);
    }

    #[Computed]
    public function inspectionObject(): InspectionObject
    {
        return InspectionObject::findOrFail($this->inspectionObjectId);
    }

    #[Computed]
    public function intro(): string
    {
        return $this->form->name . ' · ' . $this->inspectionObject->name;
    }

    public function mount(string $formSlug, int $inspectionObjectId, ?string $inspectionHash = null): void
    {
        $this->formSlug = $formSlug;
        $this->inspectionObjectId = $inspectionObjectId;
        $this->inspectionHash = $inspectionHash;

        $this->submissionForm->init($this->inspection, $this->form);
    }

    #[On(Event::INSPECTABLE_SAVED)]
    public function onInspectableSaved(int $inspectableId, string $inspectableType): void
    {
        $this->inspection->inspectable_id = $inspectableId;
        $this->inspection->inspectable_type = $inspectableType;
        $this->inspection->save();

        unset($this->inspection);
    }

    #[On(Event::INSPECTION_SAVED)]
    public function onInspectionSaved(string $inspectionHash): void {
        if (!$this->inspectionHash) {
            $this->redirect(route('inspections.form', [
                'formSlug' => $this->formSlug,
                'inspectionObjectId' => $this->inspectionObjectId,
                'inspectionHash' => $inspectionHash,
            ]), navigate: true);

            return;
        }

        unset($this->client);
        unset($this->inspection);
    }

    #[On(Event::INSPECTION_OBJECT_SAVED)]
    public function onInspectionObjectSaved(): void
    {
        unset($this->inspectionObject);
    }

    public function render()
    {
        return $this->view()
            ->title(__('inspections.create.title'));
    }

    public function fetchFromOutsmart(OutsmartService $outsmart): void
    {
        $inspection = $this->inspection;

        if (!$inspection->exists || !$inspection->outsmart_work_order_id) {
            return;
        }

        $workOrder = $outsmart->getWorkOrder($inspection->outsmart_work_order_id);

        if ($workOrder === null) {
            $this->dispatch(Event::TOAST, message: __('inspections.form.outsmart.toast.not_found'), type: 'error');

            return;
        }

        $employeeNr = $workOrder['EmployeeNr'] ?? null;
        $employee = $employeeNr ? $outsmart->getEmployee((string) $employeeNr) : null;
        $inspectorName = $employee
            ? (trim(($employee['firstname'] ?? '') . ' ' . ($employee['lastname'] ?? '')) ?: null)
            : null;

        $inspection->update([
            'project_name' => ($workOrder['Reference'] ?? null) ?: ($workOrder['OrderNr'] ?? null),
            'project_address' => trim(($workOrder['CustomerStreet'] ?? '') . ' ' . ($workOrder['CustomerStreetNo'] ?? '')),
            'project_postal_code' => $workOrder['CustomerZIP'] ?? null,
            'project_city' => $workOrder['CustomerCity'] ?? null,
            'inspector_name' => $inspectorName,
            'outsmart_order_number' => $workOrder['OrderNr'] ?? null,
            'outsmart_photos' => $workOrder['Photos'] ?? null,
        ]);

        unset($this->inspection);

        $this->dispatch(Event::TOAST, message: __('inspections.form.outsmart.toast.fetched'), type: 'success');
    }

    public function submit(): void
    {
        $this->submissionForm->save();
        $this->dispatch(Event::SAVE_MATRIX);
    }
}
?>

<div>
    @php
        $toggleFieldKeys = $this->form->fields
            ->filter(fn($f) => $f->type === FieldType::TOGGLE)
            ->map(fn($f) => 'field_' . $f->pivot->id)
            ->values()
            ->toArray();
    @endphp
    <div x-data="{
        keys: @js($toggleFieldKeys),
        get allTogglesCompleted() {
            if (this.keys.length === 0) {
                return true;
            }

            return this.keys.every(key => {
                const val = $wire.submissionForm.fields[key];
                return val !== null && val !== undefined;
            });
        },
        get allTogglesPassed() {
            if (this.keys.length === 0) {
                return true;
            }

            return this.keys.every(key => {
                const val = $wire.submissionForm.fields[key];
                return val === 0 || val === 1 || val === '0' || val === '1';
            });
        },
    }">
    <x-header
        :intro="$this->intro"
        :title="$this->inspection->project_name ?: __('inspections.create.title')">
        @if ($this->inspection->exists && $this->inspection->outsmart_work_order_id)
            <x-slot:actions>
                <x-btn
                    icon="refresh-cw"
                    primary
                    wire:click="fetchFromOutsmart"
                    wire:loading.attr="disabled"
                    wire:loading.class="is-spinning"
                    wire:target="fetchFromOutsmart"
                >@lang('inspections.form.btn_fetch_outsmart')</x-btn>
            </x-slot:actions>
        @endif
    </x-header>
    <x-form class="form form--full u-stack u-stack-gap-xl">
        <div class="grid grid--gap-xxl">
            <div class="grid__col l:grid__col--span-4">
                <div class="u-stack u-stack-gap-xl">
                    {{-- Project --}}
                    <div class="u-stack u-stack-gap-m">
                        <header class="u-flex u-flex-align-center u-flex-justify-between">
                            <h2>@lang('inspections.form.heading_project')</h2>
                            <x-btn
                                :icon="$this->inspection->exists ? 'pencil' : 'plus'"
                                small
                                wire:click.prevent="$dispatch('openModal', {
                                    component: 'inspections.start-inspection-modal',
                                    arguments: {
                                        formId: {{ $this->form->id }},
                                        inspectionObjectId: {{ $this->inspectionObjectId }},
                                        inspectionHash: {{ $this->inspection->exists ? '\'' . $this->inspection->hash . '\'' : 'null' }},
                                    }
                                })"
                            />
                        </header>
                        @if ($this->inspection->exists)
                            <div>
                                @if ($this->inspection->type)
                                    <x-data-item :label="__('models/inspection.type.label')" :value="$this->inspection->type->label()" />
                                @endif
                                @if ($this->inspection->outsmart_order_number)
                                    <x-data-item :label="__('models/inspection.outsmart_order_number.label')" :value="$this->inspection->outsmart_order_number" />
                                @endif
                                @if ($this->inspection->inspector_name)
                                    <x-data-item :label="__('models/inspection.inspector_name.label')" :value="$this->inspection->inspector_name" />
                                @endif
                                @if ($this->inspection->project_name)
                                    <x-data-item :label="__('models/inspection.project_name.label')" :value="$this->inspection->project_name" />
                                @endif
                                @if ($this->inspection->client)
                                    <x-data-item :label="__('models/inspection.client_id.label')" :value="$this->inspection->client->name" />
                                @endif
                                @if ($this->inspection->project_address || $this->inspection->project_postal_code || $this->inspection->project_city)
                                    @php
                                        $formattedAddress = collect([
                                            $this->inspection->project_address,
                                            trim($this->inspection->project_postal_code . ' ' . $this->inspection->project_city)
                                        ])->filter()->implode('<br>');

                                        $addressParam = collect([
                                            $this->inspection->project_address,
                                            trim($this->inspection->project_postal_code . ' ' . $this->inspection->project_city)
                                        ])->filter()->implode(', ');

                                        $addressUrl = 'https://www.google.com/maps/place/' . urlencode($addressParam);
                                        $address = '<a href="' . $addressUrl . '" target="_blank">' . $formattedAddress . '</a>';
                                    @endphp
                                    <x-data-item
                                        :label="__('models/inspection.project_address.label')"
                                        :value="$address"
                                    />
                                @endif
                            </div>
                        @else
                            <p class="u-muted">—</p>
                        @endif
                    </div>
                    {{-- Object --}}
                    <div class="u-stack u-stack-gap-m">
                        <header class="u-flex u-flex-align-center u-flex-justify-between">
                            <h2>@lang('inspections.form.heading_object')</h2>
                            <x-btn
                                icon="pencil"
                                small
                                wire:click.prevent="$dispatch('openModal', {
                                    component: 'inspection-objects.inspection-object-modal',
                                    arguments: {
                                        id: {{ $this->inspectionObject->id }},
                                        type: '{{ $this->inspectionObject->type->value }}'
                                    }
                                })"
                            />
                        </header>
                        @if ($this->inspectionObject)
                            <x-inspection-object :inspectionObject="$this->inspectionObject" />
                        @endif
                    </div>
                    {{-- Inspectable --}}
                    <div class="u-stack u-stack-gap-m">
                        <header class="u-flex u-flex-align-center u-flex-justify-between">
                            @if ($this->inspectionObject->type === InspectionObjectType::CRANE)
                                <h2>@lang('inspections.form.heading_crane')</h2>
                            @else
                                <h2>@lang('inspections.form.heading_operator_lift')</h2>
                            @endif
                            <x-btn
                                :icon="$this->inspection->inspectable ? 'pencil' : 'plus'"
                                small
                                wire:click.prevent="$dispatch('openModal', {
                                    component: 'inspections.inspectable-modal',
                                    arguments: {
                                        type: '{{ $this->inspectionObject->type->value }}',
                                        inspectableId: {{ $this->inspection->inspectable->id ?? 'null' }}
                                    }
                                })"
                            />
                        </header>
                        @if ($this->inspection->inspectable instanceof Crane)
                            @include('pages.inspections._inspectable-crane', ['crane' => $this->inspection->inspectable])
                        @elseif ($this->inspection->inspectable instanceof OperatorLift)
                            @include('pages.inspections._inspectable-operator-lift', ['operatorLift' => $this->inspection->inspectable])
                        @endif
                    </div>
                </div>
            </div>
            <div class="grid__col l:grid__col--span-8">
                <div class="u-stack u-stack-gap-xl">
                    @if ($this->inspection->exists)
                        @php
                            $items = FormItems::get($this->form);
                        @endphp
                        <div class="u-stack u-stack-gap-m">
                            <h2>{{ $this->form->name }}</h2>
                            <div class="inspection">
                                @foreach ($items as $item)
                                    @switch($item['type'])
                                        @case('fieldGroup')
                                            @if($item['fieldGroup']->fields->count() > 0)
                                                <x-submission.field-group :field-group="$item['fieldGroup']" :form="$this->submissionForm" />
                                            @endif
                                            @break
            
                                        @case('field')
                                            <x-submission.field :field="$item['field']" :form="$this->submissionForm" />
                                            @break
            
                                        @case('formComment')
                                            <x-submission.form-comment :form-comment="$item['formComment']" />
                                            @break
                                    @endswitch
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="emptyState">
                            <div class="emptyState__icon">
                                <x-icon icon="info" />
                            </div>
                            <div class="u-stack u-stack-gap-xs">
                                <h3 class="emptyState__title">@lang('inspections.form.empty.title')</h3>
                                <p class="emptyState__description">@lang('inspections.form.empty.description')</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if (!empty($this->inspection->outsmart_photos) && is_array($this->inspection->outsmart_photos))
            <div class="u-stack u-stack-gap-l">
                <h3>Outsmart Photos</h3>
                <div class="grid grid--gap-m">
                    @foreach($this->inspection->outsmart_photos as $photo)
                        <div class="outsmart-photo">
                            <img src="{{ $photo['image'] }}" alt="" class="outsmart-photo__img" style="max-width: 200px; max-height: 200px;" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        @if ($this->inspection->exists)
            <livewire:inspections.test-matrix :inspection-hash="$this->inspectionHash" />
        @endif
        <div class="grid grid--end grid--gap-xxl">
            <div class="grid__col l:grid__col--span-8">
                <div class="u-stack u-stack-gap-xl">
                    <div class="u-stack u-stack-gap-l">
                        <div class="status status--neutral" x-cloak x-show="!allTogglesCompleted">
                            <x-icon icon="hourglass" />
                            Keuringsschema nog niet voltooid.
                        </div>
                        <div class="status status--danger" x-cloak x-show="allTogglesCompleted && !allTogglesPassed">
                            <x-icon icon="octagon-x" />
                            Object afgekeurd.
                        </div>
                        <div class="status status--success" x-cloak x-show="allTogglesCompleted && allTogglesPassed">
                            <x-icon icon="check" />
                            Object goedgekeurd.
                        </div>
                        <div class="u-stack u-stack-gap-m" x-cloak x-show="allTogglesCompleted">
                            <x-form.textarea
                                :label="__('models/inspection.comment.label')"
                                model="submissionForm.inspectionComment"
                            />
                            <div class="u-stack u-stack-gap-s">
                                @if (count($this->submissionForm->inspectionImages))
                                    <div class="u-stack u-stack-gap-xs">
                                        @foreach ($this->submissionForm->inspectionImages as $image)
                                            <div class="upload__file">
                                                <x-icon icon="image" />
                                                @if ($image instanceof TemporaryUploadedFile)
                                                    <div class="upload__fileName">{{ $image->getClientOriginalName() }}</div>
                                                    <a
                                                        class="upload__fileAction"
                                                        href="{{ $image->temporaryUrl() }}"
                                                        download="{{ $image->getClientOriginalName() }}"
                                                    ><x-icon icon="download" /></a>
                                                    <button
                                                        class="upload__fileAction"
                                                        wire:click="deleteInspectionImage('{{ $image->getClientOriginalName() }}')"
                                                        wire:confirm="@lang('ui.delete_confirm')"
                                                        type="button"
                                                    ><x-icon icon="trash" /></button>
                                                @else
                                                    <div class="upload__fileName">{{ basename($image) }}</div>
                                                    <a
                                                        class="upload__fileAction"
                                                        wire:click="downloadInspectionImage('{{ $image }}')"
                                                    ><x-icon icon="download" /></a>
                                                    <button
                                                        class="upload__fileAction"
                                                        wire:click="deleteInspectionImage('{{ $image }}')"
                                                        wire:confirm="@lang('ui.delete_confirm')"
                                                        type="button"
                                                    ><x-icon icon="trash" /></button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="u-flex u-flex-gap-s">
                                    <x-submission.image-upload-button model="submissionForm.inspectionImages" />
                                </div>
                            </div>
                        </div>
                        <div class="u-stack u-stack-gap-m" x-cloak x-show="allTogglesCompleted && !allTogglesPassed">
                            <x-form.lightswitch
                                :text="__('models/inspection.has_cat_a_deficiencies.label')"
                                wire:model="submissionForm.has_cat_a_deficiencies"
                            />
                            <x-form.lightswitch
                                :text="__('models/inspection.has_cat_b_deficiencies.label')"
                                wire:model="submissionForm.has_cat_b_deficiencies"
                            />
                            <x-form.lightswitch wire:model="submissionForm.requires_reinspection" :text="__('models/inspection.requires_reinspection.label')" />
                            <x-form.lightswitch wire:model="submissionForm.requires_written_deregistration" :text="__('models/inspection.requires_written_deregistration.label')" />
                            <x-form.lightswitch wire:model="submissionForm.has_no_sticker_provided" :text="__('models/inspection.has_no_sticker_provided.label')" />
                        </div>
                    </div>
                    <div class="actions">
                        <x-btn primary submit>@lang('ui.save')</x-btn>
                        @if ($this->inspection->exists)
                            <x-btn
                                icon="download"
                                :href="route('inspection.pdf', $this->inspection->hash)"
                                :navigate="false"
                                x-cloak
                                x-show="allTogglesCompleted"
                            >@lang('inspections.form.btn_download_report')</x-btn>
                            <span>
                                @lang('ui.or')
                                <x-btn text href="{{ route('inspection-objects.show', $this->inspectionObject->id) }}">@lang('ui.cancel')</x-btn>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-form>
    </div>
</div>
