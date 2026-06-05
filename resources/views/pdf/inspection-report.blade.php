@use('App\Enums\FieldType')
@use('App\Enums\InspectionType')
@use('App\Lib\FormItems')
@use('App\Models\InspectionObjects\Crane')
@use('App\Models\InspectionObjects\OperatorLift')

@php
    $client      = $inspection->client;
    $object      = $inspection->inspectionObject;
    $form        = $inspection->form;
    $inspectable = $inspection->inspectable;
    $meta        = $inspection->meta_data ?? [];
    $isCrane     = $inspectable instanceof Crane;

    $reportNumber    = $meta['report_number'] ?? null;
    $stickerNumber   = $meta['sticker_number'] ?? null;
    $nextPeriodical  = $meta['next_periodical_date'] ?? null;
    $nextTcvt        = $meta['next_tcvt_date'] ?? null;

    $docRef = implode('_', array_filter([
        $inspection->created_at->format('Ymd'),
        strtolower($inspection->hash),
        $object?->serial_number ? strtolower(str_replace(' ', '-', $object->serial_number)) : null,
        $object?->manufacturer  ? strtolower(str_replace(' ', '-', $object->manufacturer))  : null,
        $object?->model         ? strtolower(str_replace([' ', '/'], '-', $object->model))  : null,
    ]));

    $result = match (true) {
        $inspection->has_cat_a_deficiencies => 'Categorie A tekortkoming',
        $inspection->has_cat_b_deficiencies => 'Categorie B tekortkoming',
        default => 'Goedgekeurd',
    };

    $typeLabel   = $inspection->type->label();
    $objectLabel = $object ? strtoupper($object->type->label()) : '';

    $coverTitle = match ($inspection->type) {
        InspectionType::TCVT       => 'KEURINGSRAPPORT PERIODIEK / TCVT',
        InspectionType::PERIODICAL => 'KEURINGSRAPPORT PERIODIEK',
    };

    $tableTypeLabel = match ($inspection->type) {
        InspectionType::TCVT       => 'TCVT W3-01:2025 (24-V11)',
        InspectionType::PERIODICAL => 'PERIODIEK',
    };

    $commentData  = collect($inspection->comment_data ?? []);
    $catAComments = $commentData->where('category', 'a')->values();
    $catBComments = $commentData->where('category', 'b')->values();
@endphp
<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="UTF-8" />
        <title>Keuringsrapport</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100..900&display=swap" rel="stylesheet">
        <style type="text/css">
            @include('pdf._style')
        </style>
    </head>
    <body>
        <header class="header">
            <img alt="Lifting Inspections" class="header__logo" src="http://app.liftinginspections.nl/assets/img/pdf/lifting-inspections-icon.svg" />
        </header>
        <footer class="footer">
            <div class="footer__left">
                Lifting Inspections B.V.<br />
                Nijverheidsweg 1A<br />
                3433NP Nieuwegein<br />
                KvK 23047245
            </div>
            <div class="footer__center">
                p. <span class="footer__pageNo"></span>
            </div>
            <div class="footer__right">
                <a href="https://www.rva.nl/alle-geaccrediteerden/i385/" target="_blank"><img alt="RVA Inspectie I385" class="footer__logo" src="http://app.liftinginspections.nl/assets/img/pdf/rva-i385.svg"></a>
            </div>
        </footer>
        <div class="cover">
            <header class="cover__header">
                <img alt="Lifting Inspections" class="cover__logo" src="http://app.liftinginspections.nl/assets/img/pdf/lifting-inspections.svg">
            </header>
            <div class="cover__content">
                <h1>{{ $coverTitle }}</h1>
                <h2>{{ $form->name }}</h2>
            </div>
            <footer class="cover__footer">
                De inhoud van dit rapport is in overeenstemming met het TCVT-keuringsschema identificatiecode: TCVT W3-01: 2025 (24-V11) voor het periodiek keuren van hijskranen.
            </footer>
        </div>
        <!-- Intro -->
        @include('pdf._intro-tcvt')
        <!-- Section -->
        <div class="page-break-after">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2" scope="col">Type keuring: {{ $tableTypeLabel }}</th>
                    </tr>
                </thead>
                <tbody>
                    <x-pdf.row label="Rapportnummer" :value="$reportNumber ?? '—'" />
                    <x-pdf.row label="Inspectiedatum" :value="$inspection->created_at->isoFormat('D-M-YYYY')" />
                    <x-pdf.row label="Inspecteur" :value="$inspection->user->name ?? '—'" />
                    <x-pdf.row label="Bevindingen" :value="$result" />
                    <x-pdf.row label="Volgende periodieke keuring voor" :value="$nextPeriodical ?? '—'" />
                    <x-pdf.row label="Volgende TCVT keuring voor" :value="$nextTcvt ?? '—'" />
                    <x-pdf.row label="Stickernummer" :value="$stickerNumber ?? '—'" />
                </tbody>
            </table>
            @if ($client)
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="2" scope="col">Opdrachtgever</th>
                        </tr>
                    </thead>
                    <tbody>
                        <x-pdf.row :label="__('models/client.name.label')" :value="$client->name" />
                        <x-pdf.row :label="__('models/client.address.label')" :value="$client->address . '<br>' . $client->postal_code . ' ' . $client->city" />
                        <x-pdf.row :label="__('models/client.contact_name.label')" :value="$client->contact_name" />
                    </tbody>
                </table>
            @endif
            <!-- Project -->
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2" scope="col">Projectgegevens</th>
                    </tr>
                </thead>
                <tbody>
                    <x-pdf.row :label="__('models/inspection.project_name.label')" :value="$inspection->project_name" />
                    <x-pdf.row :label="__('models/inspection.project_address.label')" :value="$inspection->project_address . '<br>' . $inspection->project_postal_code . ' ' . $inspection->project_city" />
                </tbody>
            </table>
            <!-- Inspectable -->
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2" scope="col">{{ $inspectable->type->label() }}</th>
                    </tr>
                </thead>
                <tbody>
                    <x-pdf.row :label="__('models/inspection_object.manufacturer.label')" :value="$object->manufacturer" />
                    <x-pdf.row :label="__('models/inspection_object.model.label')" :value="$object->model" />
                    <x-pdf.row :label="__('models/inspection_object.serial_number.label')" :value="$object->serial_number" />
                    <x-pdf.row :label="__('models/inspection_object.year_manufacture.label')" :value="$object->year_manufacture" />
                </tbody>
            </table>
            @if ($inspectable instanceof Crane)
                @include('pdf._inspectable-crane', ['crane' => $inspectable])
            @elseif ($inspectable instanceof OperatorLift)
                @include('pdf._inspectable-operator-lift', ['operatorLift' => $inspectable])
            @endif
        </div>
        <!-- Form groups -->
        @php
            $formItems = FormItems::get($inspection->form);

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
                @endphp
                <table class="table table--form">
                    <thead>
                        <tr>
                            <th class="table__fieldNum">{{ $fieldGroup->number }}</th>
                            <th>{{ $fieldGroup->name }}</th>
                            <th class="table__toggleCol"><img alt="" height="16" src="https://app.liftinginspections.nl/assets/img/pdf/check-wh.svg" width="16"></th>
                            <th class="table__toggleCol"><img alt="" height="16" src="https://app.liftinginspections.nl/assets/img/pdf/x-wh.svg" width="16"></th>
                            <th class="table__toggleCol"><img alt="" height="16" src="https://app.liftinginspections.nl/assets/img/pdf/circle-slash-wh.svg" width="16"></th>
                        </tr>
                    </thead>
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
                                @else
                                    <tr>
                                        <td class="table__fieldNum">{{ $field->number }}</td>
                                        <td colspan="4">
                                            <div class="table__formQ">{{ nl2br(e($field->label)) }}</div>
                                            <div class="table__formA">{{ $answer }}</div>
                                        </td>
                                    </tr>
                                @endif
                            @elseif ($groupItem['type'] === 'formComment')
                                <tr>
                                    <td class="table__formComment" colspan="5">{{ $groupItem['formComment']->comment }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
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
                        @else
                            <tr>
                                <td class="table__fieldNum">{{ $field->number }}</td>
                                <td colspan="4">
                                    <div class="table__formQ">{{ $field->label }}</div>
                                    <div class="table__formA">{{ $answer }}</div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @elseif ($item['type'] === 'formComment')
                <p class="table__formComment" style="margin-bottom: 0.5em; padding: 1.5mm 2mm;">{{ $item['formComment']->comment }}</p>
            @endif
        @endforeach
        <!-- Test matrix -->
        @if ($isCrane && ! empty($inspection->matrix))
            @include('pdf._test-matrix')
        @endif
    </body>
</html>
