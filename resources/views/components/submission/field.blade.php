@use('App\Enums\FieldType')

@props([
    'field' => null,
])

<div class="submission__field">
    @switch($field->type)
        {{--
        @case (FieldType::DOCUMENT)
            <x-form.upload
                :description="$field->description"
                :files="$this->fields['field_' . $field->pivot->id]"
                :label="$field->label"
                :multiple="($field->attrs['allow_multiple'] ?? false) === true"
                :required="$field->pivot->required == 1"
                wire:model.live="fields.field_{{ $field->pivot->id }}"
            />
            @break;

        @case (FieldType::IMAGE)
            <x-form.upload
                accept="image/*"
                :description="$field->description"
                :files="$this->fields['field_' . $field->pivot->id]"
                :label="$field->label"
                :multiple="($field->attrs['allow_multiple'] ?? false) === true"
                :required="$field->pivot->required == 1"
                wire:model.live="fields.field_{{ $field->pivot->id }}"
            />
            @break;
        --}}

        @case (FieldType::TOGGLE)
            <x-form.yes-no
                model="form.fields.field_{{ $field->pivot->id }}"
                :required="$field->pivot->required == 1"
                :text="$field->numberedLabel"
            />
            <div class="submission__comment" x-cloak x-show="$wire.form.fields.field_{{ $field->pivot->id }} == -1">
                <x-form.input
                    model="form.comments.field_{{ $field->pivot->id }}"
                    :placeholder="__('inspection.form.comment')"
                    type="text"
                    x-bind:required="$wire.fields.field_{{ $field->pivot->id }} == -1"
                />
            </div>
            {{--
            <x-form.lightswitch
                model="fields.field_{{ $field->pivot->id }}"
                :text="$field->label"
            />
            --}}
            @break;

        @case (FieldType::NUMBER)
            <x-form.input
                :description="$field->description"
                :label="$field->label"
                model="form.fields.field_{{ $field->pivot->id }}"
                :required="$field->pivot->required == 1"
                type="number"
            />
            @break;

        @case (FieldType::SELECT)
            <x-form.select
                default="—"
                :description="$field->description"
                :label="$field->label"
                model="form.fields.field_{{ $field->pivot->id }}"
                :options="$field->options"
                :required="$field->pivot->required == 1"
            />
            @break;

        @case (FieldType::SELECT_MULTIPLE)
            <x-form.options
                :description="$field->description"
                :label="$field->label"
                model="form.fields.field_{{ $field->pivot->id }}"
                :options="$field->options"
                :required="$field->pivot->required == 1"
            />
            @break;

        @case (FieldType::TEXT)
            <x-form.input
                :description="$field->description"
                :label="$field->label"
                model="form.fields.field_{{ $field->pivot->id }}"
                :required="$field->pivot->required == 1"
            />
            @break;

        @case (FieldType::TEXTAREA)
            <x-form.textarea
                :description="$field->description"
                :label="$field->label"
                model="form.fields.field_{{ $field->pivot->id }}"
                :required="$field->pivot->required == 1"
            />
            @break;
    @endswitch
</div>