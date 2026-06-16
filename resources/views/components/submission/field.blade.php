@use('App\Enums\FieldType')

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
            @php
                $fieldKey = 'field_'.$field->pivot->id;
                $selectedPhotos = array_values($form->images[$fieldKey] ?? []);
            @endphp
            <x-form.yes-no
                :meta-field-id="$field->pivot->id"
                model="submissionForm.fields.{{ $fieldKey }}"
                :required="$field->pivot->required == 1"
                :text="$field->numberedLabel"
            />
            <div
                class="submission__comment u-stack u-stack-gap-m"
                x-cloak
                x-data="{ fieldKey: '{{ $fieldKey }}', title: @js(strip_tags($field->numberedLabel)) }"
                x-show="$wire.submissionForm.fields.{{ $fieldKey }} == -1"
            >
                <div class="u-flex u-flex-gap-s">
                    <div class="u-flex-flex">
                        <x-form.input
                            model="submissionForm.comments.{{ $fieldKey }}"
                            :placeholder="__('inspection.form.comment')"
                            type="text"
                            x-bind:required="$wire.fields.{{ $fieldKey }} == -1"
                        />
                    </div>
                    <x-btn icon="image" type="button" x-on:click="$dispatch('photo-picker-open', {
                        fieldKey,
                        title,
                        selected: $refs.thumbs ? Array.from($refs.thumbs.querySelectorAll('img')).map((img) => img.getAttribute('src')) : [],
                    })">
                        @lang('inspections.form.select_photos')
                    </x-btn>
                </div>
                @if (count($selectedPhotos))
                    <div class="submission__photoThumbs" x-ref="thumbs">
                        @foreach ($selectedPhotos as $photoUrl)
                            <div class="submission__photoThumb" wire:key="thumb-{{ $fieldKey }}-{{ $loop->index }}">
                                <img alt="" loading="lazy" src="{{ $photoUrl }}" x-on:click="$dispatch('photo-picker-open', {
                                    fieldKey,
                                    title,
                                    selected: Array.from($refs.thumbs.querySelectorAll('img')).map((img) => img.getAttribute('src')),
                                })" />
                                <button
                                    class="submission__photoThumb-remove"
                                    type="button"
                                    wire:click="removeFieldPhoto('{{ $fieldKey }}', {{ $loop->index }})"
                                    wire:loading.attr="disabled"
                                ><x-icon icon="x" /></button>
                            </div>
                        @endforeach
                    </div>
                @endif
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