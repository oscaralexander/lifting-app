<?php

use App\Constants\SessionKey;
use App\Enums\CountryCode;
use App\Enums\FieldType;
use App\Lib\FormItems;
use App\Livewire\Forms\InspectionForm;
use App\Models\Inspection;
use App\Models\InspectionObject;
use App\Models\Form;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;

new class extends Component
{
    public InspectionForm $form;

    #[Locked]
    public ?string $inspectionHash = null;

    #[Locked]
    public int $inspectionObjectId;

    #[Locked]
    public string $formSlug;

    #[Computed]
    public function schema(): Form
    {
        return Form::where('slug', $this->formSlug)->firstOrFail();
    }

    #[Computed]
    public function inspection(): Inspection
    {
        if ($this->inspectionHash) {
            return Inspection::where('hash', $this->inspectionHash)->firstOrFail();
        }

        return new Inspection();
    }

    #[Computed]
    public function inspectionObject(): InspectionObject
    {
        return InspectionObject::findOrFail($this->inspectionObjectId);
    }

    #[Computed]
    public function intro(): string
    {
        return $this->schema->name . ' · ' . $this->inspectionObject->inspectable?->name;
    }

    public function mount(int $inspectionObjectId, string $formSlug, ?string $inspectionHash = null): void
    {
        $this->formSlug = $formSlug;
        $this->inspectionObjectId = $inspectionObjectId;
        $this->inspectionHash = $inspectionHash;

        $this->form->init($this->inspection, $this->schema);
    }

    public function postalCodePattern(): string
    {
        return match ($this->form->project_country) {
            CountryCode::NL => '/^\d{4}\s?[A-Za-z]{2}$/',
            CountryCode::BE => '/^\d{4}$/',
            CountryCode::FR => '/^\d{5}$/',
        };
    }

    public function postalCodePlaceholder(): string
    {
        return match ($this->form->project_country) {
            CountryCode::NL => '1234 AB',
            CountryCode::BE => '1000',
            CountryCode::FR => '75001',
        };
    }

    public function render()
    {
        return $this->view()
            ->title(__('inspections.create.title'));
    }

    public function rules(): array
    {
        $rules = [
            'form.project_name' => ['required', 'string', 'max:255'],
            'form.project_address' => ['nullable', 'string', 'max:255'],
            'form.project_postal_code' => ['nullable', 'string', 'max:20'],
            'form.project_city' => ['nullable', 'string', 'max:255'],
            'form.project_country' => ['required'],
        ];

        foreach ($this->schema->fields as $field) {
            $fieldRules = [];
            $key = 'form.fields.field_' . $field->pivot->id;

            if ($field->pivot->required == 1) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            if ($field->type === FieldType::NUMBER) {
                $fieldRules[] = 'numeric';
            }

            if ($field->type === FieldType::TOGGLE) {
                $fieldRules[] = 'in:-1,0,1';
            }

            $rules[$key] = $fieldRules;
        }

        return $rules;
    }

    public function submit(): void
    {
        $this->validate(rules: $this->rules());

        $formData = $this->form->fields;

        foreach ($formData as $key => $value) {
            if (is_array($value)) {
                $pivotId = Str::of($key)->afterLast('_')->toInteger();
                $field = $this->schema->fields->firstWhere('pivot.id', $pivotId);

                if ($field && $field->type === FieldType::SELECT_MULTIPLE) {
                    $formData[$key] = implode(',', array_keys(array_filter($value)));
                }
            }
        }

        $formData = array_filter($formData, fn ($value) => $value !== null);

        $inspection = $this->inspection;
        $inspection->inspection_object_id = $this->inspectionObject->id;
        $inspection->form_id = $this->schema->id;
        $inspection->user_id = auth('web')->id();
        $inspection->project_name = $this->form->project_name;
        $inspection->project_address = $this->form->project_address;
        $inspection->project_postal_code = $this->form->project_postal_code;
        $inspection->project_city = $this->form->project_city;
        $inspection->project_country = $this->form->project_country->value;
        $inspection->form_data = $formData;
        $comments = $this->form->comments;

        foreach ($this->schema->fields as $field) {
            if ($field->type === FieldType::TOGGLE) {
                $key = 'field_' . $field->pivot->id;

                if (($formData[$key] ?? null) != -1) {
                    unset($comments[$key]);
                }
            }
        }

        $inspection->comment_data = array_filter($comments, fn ($value) => $value !== null);
        $inspection->save();

        if ($inspection->wasRecentlyCreated) {
            $this->redirect(route('inspections.form', [
                'formSlug' => $this->schema->slug,
                'inspectionObjectId' => $this->inspectionObject->id,
                'inspectionHash' => $inspection->hash,
            ]));

            return;
        }

        session()->flash(SessionKey::TOAST_SUCCESS, __('ui.saved'));

        $this->redirect(route('inspections.form', [
            'formSlug' => $this->schema->slug,
            'inspectionObjectId' => $this->inspectionObject->id,
            'inspectionHash' => $this->inspectionHash,
        ]));
    }
}
?>

<div>
    <x-header
        :intro="$this->intro"
        :title="__('inspections.create.title')">
    </x-header>
    <x-form class="form u-stack u-stack-gap-xl">
        <div class="grid grid--gap-m">
            <div class="grid__col l:grid__col--span-6">
                <div class="u-stack u-stack-gap-m">
                    <h2>Projectgegevens</h2>
                    <div class="grid grid--gap-m">
                        <div class="grid__col">
                            <x-form.input
                                :label="__('models/inspection.project_name.label')"
                                model="form.project_name"
                            />
                        </div>
                        <div class="grid__col">
                            <x-form.input
                                :label="__('models/inspection.project_address.label')"
                                model="form.project_address"
                            />
                        </div>
                        <div class="grid__col l:grid__col--span-4">
                            <x-form.input
                                :label="__('models/inspection.project_postal_code.label')"
                                model="form.project_postal_code"
                                :placeholder="$this->postalCodePlaceholder()"
                            />
                        </div>
                        <div class="grid__col l:grid__col--span-8">
                            <x-form.input
                                :label="__('models/inspection.project_city.label')"
                                model="form.project_city"
                            />
                        </div>
                        <div class="grid__col">
                            <x-form.select
                                :label="__('clients.form.country.label')"
                                wire:model.live="form.project_country"
                                :options="CountryCode::options()"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            $items = FormItems::get($this->schema);
        @endphp
        @dump($form)
        <div class="u-stack u-stack-gap-m">
            <h2>{{ $this->schema->name }}</h2>
            <div class="inspection">
                @foreach ($items as $item)
                    @switch($item['type'])
                        @case('fieldGroup')
                            @if($item['fieldGroup']->fields->count() > 0)
                                <x-submission.field-group :field-group="$item['fieldGroup']" />
                            @endif
                            @break

                        @case('field')
                            <x-submission.field :field="$item['field']" />
                            @break

                        @case('formComment')
                            <x-submission.form-comment :form-comment="$item['formComment']" />
                            @break
                    @endswitch
                @endforeach
            </div>
        </div>
        <x-form.lightswitch
            model="form.is_completed"
            :text="__('models/inspection.is_completed.text')"
        />
        <div class="actions">
            <x-btn primary submit>@lang('ui.save')</x-btn>
            @if ($this->inspection->exists)
                <span>
                    @lang('ui.or')
                    <x-btn text href="{{ route('inspection-objects.show', $this->inspectionObject->id) }}">@lang('ui.cancel')</x-btn>
                </span>
            @endif
        </div>
    </x-form>
</div>
