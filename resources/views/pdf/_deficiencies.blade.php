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

    $writtenDeregistrationDescription = $inspection->is_approved_after_written_deregistration
        ? $inspection->written_deregistration_description
        : null;
@endphp
@if ($deficiencies->isNotEmpty() || filled($writtenDeregistrationDescription))
    <section class="deficiencies">
        <h2 class="deficiencies__heading">{{ $deficienciesHeading ?? 'Tekortkomingen' }}</h2>
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
        @if (filled($writtenDeregistrationDescription))
            <div class="deficiency">
                <h3 class="deficiency__title">@lang('models/inspection.is_approved_after_written_deregistration.label')</h3>
                <p class="deficiency__comment">{!! nl2br(e($writtenDeregistrationDescription)) !!}</p>
            </div>
        @endif
    </section>
@endif