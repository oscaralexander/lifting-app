@use('App\Enums\FieldType')
@use('App\Lib\FormItems')

@php
    $formItems = FormItems::get($inspection->form);
    $selectedOptions = function ($field, $answer) {
        if ($answer === null || $answer === '') {
            return [];
        }

        $selected = array_map('intval', explode(',', (string) $answer));

        return array_values(array_intersect_key($field->options, array_flip($selected)));
    };
    $svgYes = '<img alt="" height="16" src="https://app.liftinginspections.nl/assets/img/pdf/check.svg" width="16">';
    $svgNo = '<img alt="" height="16" src="https://app.liftinginspections.nl/assets/img/pdf/x.svg" width="16">';
    $svgNa = '<img alt="" height="16" src="https://app.liftinginspections.nl/assets/img/pdf/circle-slash.svg" width="16">';
@endphp
@foreach ($formItems as $item)
    @if ($item['type'] === 'fieldGroup')
        @php
            $fieldGroup = $item['fieldGroup'];
            $groupItems = collect();

            foreach ($fieldGroup->fields->sortBy('pivot.position') as $field) {
                $groupItems->push(['type' => 'field', 'field' => $field, 'position' => $field->pivot->position ?? 0]);
            }

            foreach ($fieldGroup->formComments->sortBy('position') as $formComment) {
                $groupItems->push(['type' => 'formComment', 'formComment' => $formComment, 'position' => $formComment->position ?? 0]);
            }

            $groupItems = $groupItems->sortBy('position');

            $isNotApplicable = $groupItems->isNotEmpty() && $groupItems->every(
                fn ($groupItem) => $groupItem['type'] === 'field'
                    && $groupItem['field']->type === FieldType::TOGGLE
                    && (string) data_get($inspection->form_data, 'field_' . $groupItem['field']->pivot->id) === '0'
            );
        @endphp
        <table class="table table--form">
            <thead>
                <tr>
                    <th class="table__fieldNum">{{ $fieldGroup->number }}</th>
                    @if ($isNotApplicable)
                        <th colspan="3">{{ $fieldGroup->name }}</th>
                    @else
                        <th>{{ $fieldGroup->name }}</th>
                        <th class="table__toggleCol"><img alt="" height="16" src="https://app.liftinginspections.nl/assets/img/pdf/check-wh.svg" width="16"></th>
                        <th class="table__toggleCol"><img alt="" height="16" src="https://app.liftinginspections.nl/assets/img/pdf/x-wh.svg" width="16"></th>
                    @endif
                    <th class="table__toggleCol"><img alt="" height="16" src="https://app.liftinginspections.nl/assets/img/pdf/circle-slash-wh.svg" width="16"></th>
                </tr>
            </thead>
            @unless ($isNotApplicable)
                <tbody>
                    @foreach ($groupItems as $groupItem)
                        @if ($groupItem['type'] === 'field')
                            @php
                                $field  = $groupItem['field'];
                                $answer = data_get($inspection->form_data, 'field_' . $field->pivot->id);
                                $isYes  = $answer !== null && (string) $answer === '1';
                                $isNo   = $answer !== null && (string) $answer === '-1';
                                $isNa   = $answer !== null && (string) $answer === '0';
                            @endphp
                            @if ($field->type === FieldType::TOGGLE)
                                <tr>
                                    <td class="table__fieldNum">{{ $field->number }}</td>
                                    <td>{!! nl2br($field->label) !!}</td>
                                    <td class="table__toggleCol table__toggleCol--yes">{!! $isYes ? $svgYes : '' !!}</td>
                                    <td class="table__toggleCol table__toggleCol--no">{!! $isNo ? $svgNo : '' !!}</td>
                                    <td class="table__toggleCol table__toggleCol--na">{!! $isNa ? $svgNa : '' !!}</td>
                                </tr>
                            @elseif ($field->type === FieldType::SELECT_MULTIPLE)
                                @php $options = $selectedOptions($field, $answer); @endphp
                                <tr>
                                    <td class="table__fieldNum">{{ $field->number }}</td>
                                    <td>{!! implode('<hr>', array_map(fn ($option) => nl2br(e($option)), $options)) !!}</td>
                                    <td class="table__toggleCol table__toggleCol--yes">{!! $options ? $svgYes : '' !!}</td>
                                    <td colspan="2"></td>
                                </tr>
                            @elseif ($field->type === FieldType::IMAGE)
                                <tr>
                                    <td class="table__fieldNum">{{ $field->number }}</td>
                                    <td>
                                        <div class="answer">
                                            <div class="answer__label">{{ nl2br(e($field->label)) }}</div>
                                            <div class="answer__answer">
                                                @if ($answer)
                                                    <img alt="" src="{{ $answer }}">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td colspan="3"></td>
                                </tr>
                            @else
                                <tr>
                                    <td class="table__fieldNum">{{ $field->number }}</td>
                                    <td>
                                        <div class="answer">
                                            <div class="answer__label">{{ nl2br(e($field->label)) }}</div>
                                            <div class="answer__answer">{{ $answer }}</div>
                                        </div>
                                    </td>
                                    <td colspan="3"></td>
                                </tr>
                            @endif
                        @elseif ($groupItem['type'] === 'formComment')
                            <tr>
                                <td class="table__formComment" colspan="5">{{ $groupItem['formComment']->comment }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            @endunless
        </table>
    @elseif ($item['type'] === 'field')
        @php
            $field  = $item['field'];
            $answer = data_get($inspection->form_data, 'field_' . $field->pivot->id);
            $isYes  = $answer !== null && (string) $answer === '1';
            $isNo   = $answer !== null && (string) $answer === '-1';
            $isNa   = $answer !== null && (string) $answer === '0';
        @endphp
        <table class="table table--form">
            <tbody>
                @if ($field->type === FieldType::TOGGLE)
                    <tr>
                        <td class="table__fieldNum">{{ $field->number }}</td>
                        <td>{{ $field->label }}</td>
                        <td class="table__toggleCol table__toggleCol--yes">{!! $isYes ? $svgYes : '' !!}</td>
                        <td class="table__toggleCol table__toggleCol--no">{!! $isNo ? $svgNo : '' !!}</td>
                        <td class="table__toggleCol table__toggleCol--na">{!! $isNa ? $svgNa : '' !!}</td>
                    </tr>
                @elseif ($field->type === FieldType::SELECT_MULTIPLE)
                    @php $options = $selectedOptions($field, $answer); @endphp
                    <tr>
                        <td class="table__fieldNum">{{ $field->number }}</td>
                        <td>{!! implode('<hr>', array_map(fn ($option) => nl2br(e($option)), $options)) !!}</td>
                        <td class="table__toggleCol table__toggleCol--yes">{!! $options ? $svgYes : '' !!}</td>
                        <td colspan="2"></td>
                    </tr>
                @elseif ($field->type === FieldType::IMAGE)
                    <tr>
                        <td class="table__fieldNum">{{ $field->number }}</td>
                        <td>
                            <div class="answer">
                                <div class="answer__label">{{ nl2br(e($field->label)) }}</div>
                                <div class="answer__answer">
                                    @if ($answer)
                                        <img alt="" src="{{ $answer }}">
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                @else
                    <tr>
                        <td class="table__fieldNum">{{ $field->number }}</td>
                        <td>
                            <div class="answer">
                                <div class="answer__label">{{ nl2br(e($field->label)) }}</div>
                                <div class="answer__answer">{{ $answer }}</div>
                            </div>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                @endif
            </tbody>
        </table>
    @elseif ($item['type'] === 'formComment')
        <p class="table__formComment" style="margin-bottom: 0.5em; padding: 1.5mm 2mm;">{{ $item['formComment']->comment }}</p>
    @endif
@endforeach
