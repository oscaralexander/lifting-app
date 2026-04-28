@use('App\Enums\FieldType')
@use('Illuminate\Support\Str')
@use('Livewire\Features\SupportFileUploads\TemporaryUploadedFile')

@props([
    'field' => null,
    'form' => null,
])

<div class="submission__field">
    @switch($field->type)
        {{--
        @case (FieldType::IMAGE)
            <x-form.upload
                accept="image/*"
                :description="$field->description"
                :files="$this->fields['field_' . $field->pivot->id]"
                :label="$field->label"
                :multiple="($field->attrs['allow_multiple'] ?? false) === true"
                :required="$field->pivot->required == 1"
                wire:model.live="submissionForm.fields.field_{{ $field->pivot->id }}"
            />
            @break;
        --}}

        @case (FieldType::TOGGLE)
            <x-form.yes-no
                model="submissionForm.fields.field_{{ $field->pivot->id }}"
                :meta-field-id="$field->pivot->id"
                :required="$field->pivot->required == 1"
                :text="$field->numberedLabel"
            />
            <div class="submission__comment u-stack u-stack-gap-s" x-cloak x-show="$wire.submissionForm.fields.field_{{ $field->pivot->id }} == -1">
                <div class="u-flex u-flex-gap-s">
                    <div class="u-flex-flex">
                        <x-form.input
                            model="submissionForm.comments.field_{{ $field->pivot->id }}"
                            :placeholder="__('inspection.form.comment')"
                            type="text"
                            x-bind:required="$wire.fields.field_{{ $field->pivot->id }} == -1"
                        />
                    </div>
                    <x-submission.image-upload-button :model="'submissionForm.images.field_' . $field->pivot->id" />
                </div>
                <div class="u-stack u-stack-gap-s">
                    @php
                        $images = $form->images['field_' . $field->pivot->id] ?? [];
                    @endphp
                    @if (count($images))
                        <div class="u-stack u-stack-gap-xs">
                            @foreach ($images as $image)
                                <div class="upload__file">
                                    <x-icon icon="image" />
                                    @if ($image instanceof TemporaryUploadedFile)
                                        <div class="upload__fileName">{{ $image->getClientOriginalName() }}</div>
                                        @if (Str::startsWith($image->getMimeType(), 'image/'))
                                            <a
                                                class="upload__fileAction"
                                                href="{{ $image->temporaryUrl() }}"
                                                download="{{ $image->getClientOriginalName() }}"
                                            ><x-icon icon="download" /></a>
                                        @endif
                                        <button
                                            class="upload__fileAction"
                                            wire:click="deleteImage('field_{{ $field->pivot->id }}', '{{ $image->getClientOriginalName() }}')"
                                            wire:confirm="@lang('ui.delete_confirm')"
                                            type="button"
                                        ><x-icon icon="trash" /></button>
                                    @else
                                        <div class="upload__fileName">{{ basename($image) }}</div>
                                        <a
                                            class="upload__fileAction"
                                            wire:click="downloadImage('field_{{ $field->pivot->id }}', '{{ $image }}')"
                                        ><x-icon icon="download" /></a>
                                        <button
                                            class="upload__fileAction"
                                            wire:click="deleteImage('field_{{ $field->pivot->id }}', '{{ $image }}')"
                                            wire:confirm="@lang('ui.delete_confirm')"
                                            type="button"
                                        ><x-icon icon="trash" /></button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @break;

        @case (FieldType::NUMBER)
            <x-form.input
                :description="$field->description"
                :label="$field->label"
                model="submissionForm.fields.field_{{ $field->pivot->id }}"
                :required="$field->pivot->required == 1"
                type="number"
            />
            @break;

        @case (FieldType::SELECT)
            <x-form.select
                default="—"
                :description="$field->description"
                :label="$field->label"
                model="submissionForm.fields.field_{{ $field->pivot->id }}"
                :options="$field->options"
                :required="$field->pivot->required == 1"
            />
            @break;

        @case (FieldType::SELECT_MULTIPLE)
            <x-form.options
                :description="$field->description"
                :label="$field->label"
                model="submissionForm.fields.field_{{ $field->pivot->id }}"
                :options="$field->options"
                :required="$field->pivot->required == 1"
            />
            @break;

        @case (FieldType::TEXT)
            <x-form.input
                :description="$field->description"
                :label="$field->label"
                model="submissionForm.fields.field_{{ $field->pivot->id }}"
                :required="$field->pivot->required == 1"
            />
            @break;

        @case (FieldType::TEXTAREA)
            <x-form.textarea
                :description="$field->description"
                :label="$field->label"
                model="submissionForm.fields.field_{{ $field->pivot->id }}"
                :required="$field->pivot->required == 1"
            />
            @break;
    @endswitch
</div>