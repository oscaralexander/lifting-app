@use('App\Enums\FieldType')

<!-- Deficiencies -->
@php
    $deficiencies = collect();

    foreach ($inspection->form->fields as $field) {
        if ($field->type !== FieldType::TOGGLE) {
            continue;
        }

        $key = 'field_'.$field->pivot->id;

        if ((string) data_get($inspection->form_data, $key) !== '-1') {
            continue;
        }

        $comment = data_get($inspection->comment_data, $key);
        $photos = array_values(array_filter(
            (array) data_get($inspection->image_data, $key, []),
            fn ($photo) => is_string($photo) && $photo !== '',
        ));

        if (($comment === null || $comment === '') && count($photos) === 0) {
            continue;
        }

        $deficiencies->push([
            'field' => $field,
            'comment' => $comment,
            'photos' => $photos,
        ]);
    }

    $deficiencies = $deficiencies->sortBy(fn ($deficiency) => $deficiency['field']->number)->values();
@endphp
@if ($deficiencies->isNotEmpty())
    <section class="deficiencies">
        <h2 class="deficiencies__heading">Tekortkomingen</h2>
        @foreach ($deficiencies as $deficiency)
            <div class="deficiency">
                <h3 class="deficiency__title">{{ $deficiency['field']->number }} &bull; {{ $deficiency['field']->label }}</h3>
                @if ($deficiency['comment'])
                    <p class="deficiency__comment">{{ $deficiency['comment'] }}</p>
                @endif
                @if (count($deficiency['photos']))
                    <div class="deficiency__photos">
                        @foreach ($deficiency['photos'] as $photo)
                            <img alt="" class="deficiency__photo" src="{{ $photo }}">
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </section>
@endif