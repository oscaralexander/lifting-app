<?php

namespace App\Livewire\Forms;

use App\Enums\FieldType;
use App\Models\Form;
use App\Models\Inspection;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form as LivewireForm;

class InspectionSubmissionForm extends LivewireForm
{
    public array $comments = [];

    public array $fields = [];

    public Form $form;

    public bool $has_cat_a_deficiencies = false;

    public bool $has_cat_b_deficiencies = false;

    public bool $has_no_sticker_provided = false;

    public array $images = [];

    public ?string $inspectionComment = null;

    public array $inspectionImages = [];

    public Inspection $inspection;

    public bool $is_completed = false;

    public array $meta = [];

    public bool $requires_reinspection = false;

    public bool $requires_written_deregistration = false;

    public function deleteImage(string $fieldId, string $image): void
    {
        $this->images[$fieldId] = array_filter($this->images[$fieldId], function ($i) use ($image) {
            if ($i instanceof TemporaryUploadedFile) {
                return $i->getClientOriginalName() !== $image;
            }

            return $i !== $image;
        });

        // Save the inspection
        $this->inspection->image_data = array_filter($this->images);
        $this->inspection->save();
    }

    public function init(Inspection $inspection, Form $form): void
    {
        $this->form = $form;
        $this->inspection = $inspection;
        $this->has_cat_a_deficiencies = $inspection->has_cat_a_deficiencies ?? false;
        $this->has_cat_b_deficiencies = $inspection->has_cat_b_deficiencies ?? false;
        $this->has_no_sticker_provided = $inspection->has_no_sticker_provided ?? false;
        $this->inspectionComment = $inspection->comment;
        $this->inspectionImages = $inspection->images ?? [];
        $this->meta = $inspection->meta_data ?? [];
        $this->requires_reinspection = $inspection->requires_reinspection ?? false;
        $this->requires_written_deregistration = $inspection->requires_written_deregistration ?? false;

        foreach ($form->fields as $field) {
            $key = 'field_'.$field->pivot->id;

            $this->comments[$key] = $inspection->comment_data[$key] ?? null;
            $this->images[$key] = $inspection->image_data[$key] ?? [];

            if ($field->type === FieldType::SELECT_MULTIPLE) {
                $this->fields[$key] = $inspection->exists
                    ? $inspection->getAnswerForField($field->pivot->id, [])
                    : [];
            } else {
                $this->fields[$key] = $inspection->form_data[$key] ?? null;
            }
        }
    }

    public function rules(): array
    {
        $rules = [];

        foreach ($this->form->fields as $field) {
            $fieldRules = [];
            $key = 'fields.field_'.$field->pivot->id;

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

    public function save(): void
    {
        $this->validate();

        $commentData = array_filter($this->comments, fn ($value) => ! is_null($value));
        $formData = array_filter($this->fields, fn ($value) => ! is_null($value));

        foreach ($formData as $key => $value) {
            if (is_array($value)) {
                $formData[$key] = implode(',', array_keys(array_filter($value)));
            }
        }

        $metaData = array_filter(
            array_map(
                fn ($values) => array_filter((array) $values, fn ($v) => ! is_null($v) && $v !== ''),
                $this->meta,
            ),
            fn ($values) => ! empty($values),
        );

        $this->inspection->comment = $this->inspectionComment ?: null;
        $this->inspection->comment_data = $commentData;
        $this->inspection->form_data = $formData;
        $this->inspection->has_cat_a_deficiencies = $this->has_cat_a_deficiencies;
        $this->inspection->has_cat_b_deficiencies = $this->has_cat_b_deficiencies;
        $this->inspection->has_no_sticker_provided = $this->has_no_sticker_provided;
        $this->inspection->meta_data = $metaData;
        $this->inspection->requires_reinspection = $this->requires_reinspection;
        $this->inspection->requires_written_deregistration = $this->requires_written_deregistration;
        $this->inspection->save();
    }

    public function deleteInspectionImage(string $image): void
    {
        $this->inspectionImages = array_values(array_filter(
            $this->inspectionImages,
            function ($i) use ($image) {
                if ($i instanceof TemporaryUploadedFile) {
                    return $i->getClientOriginalName() !== $image;
                }

                return $i !== $image;
            },
        ));

        $this->inspection->images = $this->inspectionImages ?: null;
        $this->inspection->save();
    }

    public function updatingImages($value, $key): void
    {
        if (count($value) > count($this->images[$key])) {
            $storedImages = [];

            foreach ($value as $file) {
                if ($file instanceof TemporaryUploadedFile) {
                    $storedImages[] = $file->storeAs(
                        name: $file->getClientOriginalName(),
                        path: implode('/', [
                            config('path.inspections.images'),
                            $this->inspection->hash,
                            $key,
                        ]),
                    );
                } else {
                    $storedImages[] = $file;
                }
            }

            $this->images[$key] = $storedImages;

            // Save the inspection
            $this->inspection->image_data = array_filter($this->images);
            $this->inspection->save();
        }
    }

    public function updatingInspectionImages($value): void
    {
        if (count($value) > count($this->inspectionImages)) {
            $storedImages = [];

            foreach ($value as $file) {
                if ($file instanceof TemporaryUploadedFile) {
                    $storedImages[] = $file->storeAs(
                        name: $file->getClientOriginalName(),
                        path: config('path.inspections.images').'/'.$this->inspection->hash,
                        options: ['disk' => 'public'],
                    );
                } else {
                    $storedImages[] = $file;
                }
            }

            $this->inspectionImages = $storedImages;

            $this->inspection->images = $storedImages;
            $this->inspection->save();
        }
    }
}
