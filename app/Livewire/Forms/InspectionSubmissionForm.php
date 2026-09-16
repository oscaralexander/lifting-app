<?php

namespace App\Livewire\Forms;

use App\Enums\FieldType;
use App\Models\Form;
use App\Models\Inspection;
use Illuminate\Support\Facades\Storage;
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

    /**
     * Picker field key used for the inspection-level photos, as opposed to the toggle fields' `field_{id}` keys.
     */
    public const PHOTOS_KEY = 'inspection';

    public array $images = [];

    public ?string $inspectionComment = null;

    public Inspection $inspection;

    public array $meta = [];

    /**
     * Inspection-level photos with an optional comment each.
     *
     * @var array<int, array{image: string, comment: string|null}>
     */
    public array $photos = [];

    public bool $requires_reinspection = false;

    public bool $requires_written_deregistration = false;

    public ?string $stickerNumber = null;

    /**
     * Stage the photo URLs selected for a given field.
     *
     * Toggle fields hold their photos in `$images`; an image field holds its single
     * photo as the field's answer. Persisted to the database when the inspection is saved.
     *
     * @param  array<int, string>  $urls
     */
    public function setFieldPhotos(string $key, array $urls): void
    {
        if ($this->fieldType($key) === FieldType::IMAGE) {
            $this->fields[$key] = $urls[0] ?? null;

            return;
        }

        $this->images[$key] = array_values(array_unique($urls));
    }

    /**
     * Remove a single staged photo from a field by its position.
     */
    public function removeFieldPhoto(string $key, int $index): void
    {
        if ($this->fieldType($key) === FieldType::IMAGE) {
            $this->fields[$key] = null;

            return;
        }

        $photos = array_values($this->images[$key] ?? []);

        unset($photos[$index]);

        $this->images[$key] = array_values($photos);
    }

    protected function fieldType(string $key): ?FieldType
    {
        return $this->form->fields
            ->first(fn ($field) => 'field_'.$field->pivot->id === $key)
            ?->type;
    }

    /**
     * Stage the inspection-level photo selection, keeping the comments of photos that remain selected.
     *
     * @param  array<int, string>  $urls
     */
    public function setInspectionPhotos(array $urls): void
    {
        $comments = collect($this->photos)->pluck('comment', 'image');

        $this->photos = collect($urls)
            ->unique()
            ->map(fn (string $url) => ['image' => $url, 'comment' => $comments->get($url)])
            ->values()
            ->all();
    }

    public function removeInspectionPhoto(int $index): void
    {
        unset($this->photos[$index]);

        $this->photos = array_values($this->photos);
    }

    /**
     * Store a photo uploaded from the photo picker alongside the other inspection
     * images and register it so it shows up in the picker on subsequent visits.
     *
     * @return string The public URL of the stored photo.
     */
    public function storeUploadedPhoto(TemporaryUploadedFile $file): string
    {
        $path = $file->store(
            path: config('path.inspections.images').'/'.$this->inspection->hash,
            options: ['disk' => 'public'],
        );

        $url = Storage::disk('public')->url($path);

        $this->inspection->uploaded_photos = [
            ...($this->inspection->uploaded_photos ?? []),
            ['image' => $url, 'title' => $file->getClientOriginalName()],
        ];
        $this->inspection->save();

        return $url;
    }

    public function init(Inspection $inspection, Form $form): void
    {
        $this->form = $form;
        $this->inspection = $inspection;
        $this->has_cat_a_deficiencies = $inspection->has_cat_a_deficiencies ?? false;
        $this->has_cat_b_deficiencies = $inspection->has_cat_b_deficiencies ?? false;
        $this->has_no_sticker_provided = $inspection->has_no_sticker_provided ?? false;
        $this->inspectionComment = $inspection->comment;
        $this->meta = $inspection->meta_data ?? [];
        $this->photos = collect($inspection->photos ?? [])
            ->filter(fn ($photo) => ! empty($photo['image']))
            ->map(fn (array $photo) => ['image' => $photo['image'], 'comment' => $photo['comment'] ?? null])
            ->values()
            ->all();
        $this->requires_reinspection = $inspection->requires_reinspection ?? false;
        $this->requires_written_deregistration = $inspection->requires_written_deregistration ?? false;
        $this->stickerNumber = $inspection->sticker_number;

        foreach ($form->fields as $field) {
            $key = 'field_'.$field->pivot->id;

            $this->comments[$key] = $inspection->comment_data[$key] ?? null;
            $this->images[$key] = array_values(array_filter(
                $inspection->image_data[$key] ?? [],
                fn ($photo) => is_string($photo) && $photo !== '',
            ));

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

        // Mirrors the Alpine `allFieldsPassed` / `allTogglesPassed` getters: completed when every
        // toggle is answered and every required non-toggle field is filled, approved when every
        // toggle is "yes" (1) or "n/a" (0).
        $toggleValues = $this->form->fields
            ->filter(fn ($field) => $field->type === FieldType::TOGGLE)
            ->map(fn ($field) => $this->fields['field_'.$field->pivot->id] ?? null);

        $requiredFieldsFilled = $this->form->fields
            ->filter(fn ($field) => $field->type !== FieldType::TOGGLE && $field->pivot->required == 1)
            ->every(function ($field) {
                $value = $this->fields['field_'.$field->pivot->id] ?? null;

                if ($field->type === FieldType::SELECT_MULTIPLE) {
                    return is_array($value) && count($value) > 0;
                }

                return ! is_null($value) && $value !== '';
            });

        $this->inspection->comment = $this->inspectionComment ?: null;
        $this->inspection->comment_data = $commentData;
        $this->inspection->form_data = $formData;
        $this->inspection->image_data = array_filter($this->images);
        $this->inspection->is_completed = $toggleValues->every(fn ($value) => ! is_null($value)) && $requiredFieldsFilled;
        $this->inspection->is_approved = $toggleValues->every(fn ($value) => in_array((string) $value, ['0', '1'], true));
        $this->inspection->sticker_number = $this->inspection->is_approved ? ($this->stickerNumber ?: null) : null;
        $this->inspection->has_cat_a_deficiencies = $this->has_cat_a_deficiencies;
        $this->inspection->has_cat_b_deficiencies = $this->has_cat_b_deficiencies;
        $this->inspection->has_no_sticker_provided = $this->has_no_sticker_provided;
        $this->inspection->meta_data = $metaData;
        $this->inspection->photos = collect($this->photos)
            ->map(fn (array $photo) => ['image' => $photo['image'], 'comment' => trim((string) ($photo['comment'] ?? '')) ?: null])
            ->values()
            ->all() ?: null;
        $this->inspection->requires_reinspection = $this->requires_reinspection;
        $this->inspection->requires_written_deregistration = $this->requires_written_deregistration;
        $this->inspection->save();
    }
}
