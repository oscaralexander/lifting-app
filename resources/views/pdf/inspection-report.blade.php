@use('App\Enums\FieldType')
@use('App\Enums\InspectionType')
@use('App\Lib\InspectionResult')
@use('App\Models\InspectionObjects\Crane')
@use('App\Models\InspectionObjects\OperatorLift')

@php
    $client      = $inspection->client;
    $object      = $inspection->inspectionObject;
    $form        = $inspection->form;
    $inspectable = $inspection->inspectable;
    $meta        = $inspection->meta_data ?? [];

    $isAttachment      = $inspection->isAttachment();
    $parentInspection  = $isAttachment ? $inspection->parentInspection : null;
    $projectInspection = $parentInspection ?? $inspection;

    $nextPeriodical = null;
    $nextTcvt = null;

    if ($inspectable instanceof OperatorLift || $isAttachment) {
        $nextPeriodical = \Carbon\Carbon::parse($inspection->inspection_date)->addMonths(12)->translatedFormat('j F Y');
    } elseif ($inspection->type === InspectionType::TCVT) {
        $nextPeriodical = \Carbon\Carbon::parse($inspection->inspection_date)->addMonths(12)->translatedFormat('j F Y');
        $nextTcvt = \Carbon\Carbon::parse($inspection->inspection_date)->addMonths(24)->translatedFormat('j F Y');
    } else {
        $nextPeriodical = \Carbon\Carbon::parse($inspection->inspection_date)->addMonths(24)->translatedFormat('j F Y');
        $nextTcvt = \Carbon\Carbon::parse($inspection->inspection_date)->addMonths(12)->translatedFormat('j F Y');
    }

    $docRef = implode('_', array_filter([
        $inspection->created_at->format('Ymd'),
        strtolower($inspection->hash),
        $object?->serial_number ? strtolower(str_replace(' ', '-', $object->serial_number)) : null,
        $object?->manufacturer  ? strtolower(str_replace(' ', '-', $object->manufacturer))  : null,
        $object?->model         ? strtolower(str_replace([' ', '/'], '-', $object->model))  : null,
    ]));

    $result = InspectionResult::html($inspection);

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
                {{ $inspection->outsmart_order_number }}/{{ $inspection->created_at->format('Ymd') }}<br>
                p. <span class="footer__pageNo"></span>
            </div>
            <div class="footer__right">
                @if ($inspection->type === InspectionType::TCVT)
                    <a href="https://www.rva.nl/alle-geaccrediteerden/i385/" target="_blank"><img alt="RVA Inspectie I385" class="footer__logo" src="http://app.liftinginspections.nl/assets/img/pdf/rva-i385.svg"></a>
                @endif
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
        <div class="signature-box">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2" scope="col">Type keuring: {{ $tableTypeLabel }}</th>
                    </tr>
                </thead>
                <tbody>
                    <x-pdf.row label="Rapportnummer" :value="$inspection->outsmart_order_number ?? '—'" />
                    <x-pdf.row label="Inspectiedatum" :value="$inspection->inspection_date?->translatedFormat('j F Y') ?? '—'" />
                    <x-pdf.row label="Inspecteur" :value="$inspection->inspector_name ?? '—'" />
                    <x-pdf.row label="Bevindingen" :value="$result" />
                    <x-pdf.row label="Volgende periodieke keuring voor" :value="$nextPeriodical ?? '—'" />
                    @unless ($inspectable instanceof OperatorLift || $isAttachment)
                        <x-pdf.row label="Volgende TCVT keuring voor" :value="$nextTcvt ?? '—'" />
                    @endunless
                    <x-pdf.row label="Stickernummer" :value="$inspection->sticker_number ?: __('models/inspection.has_no_sticker_provided.label')" />
                </tbody>
            </table>
            <div class="signature-box__signature">
                <img alt="" src="https://app.liftinginspections.nl/assets/img/signatures/{{ $inspection->user->id ?? 'default' }}.svg">
            </div>
        </div>
        @if ($client)
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2" scope="col">Opdrachtgever</th>
                    </tr>
                </thead>
                <tbody>
                    <x-pdf.row :label="__('models/client.name.label')" :value="$client->name" />
                    <x-pdf.row :label="__('models/client.address.label')" :value="$client->address . ', ' . $client->postal_code . ' ' . $client->city" />
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
                <x-pdf.row :label="__('models/inspection.project_name.label')" :value="$projectInspection->outsmart_external_reference ?? $projectInspection->project_name" />
                <x-pdf.row :label="__('models/inspection.project_address.label')" :value="$projectInspection->project_address . ', ' . $projectInspection->project_postal_code . ' ' . $projectInspection->project_city" />
                @if ($parentInspection)
                    <x-pdf.row label="Hoofdinspectie" :value="collect([
                        $parentInspection->outsmart_order_number,
                        collect([$parentInspection->inspectionObject?->type_label, $parentInspection->inspectionObject?->name])->filter()->implode(' '),
                        $parentInspection->inspectionObject?->serial_number,
                    ])->filter()->implode(' · ')" />
                @endif
            </tbody>
        </table>
        <!-- Inspectable -->
        <table class="table">
            <thead>
                <tr>
                    @if ($isAttachment)
                        <th colspan="2" scope="col">Verwisselbaar hulpstuk: {{ $object->type_label }}</th>
                    @elseif ($inspectable instanceof Crane)
                        <th colspan="2" scope="col">{{ $inspectable->type->label() }}</th>
                    @else
                        <th colspan="2" scope="col">Machinistenlift</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <x-pdf.row :label="__('models/inspection_object.manufacturer.label')" :value="$object->manufacturer" />
                <x-pdf.row :label="__('models/inspection_object.model.label')" :value="$object->model" />
                <x-pdf.row :label="__('models/inspection_object.serial_number.label')" :value="$object->serial_number" />
                @if ($isAttachment)
                    <x-pdf.row :label="__('models/inspection_object.asset_number.label')" :value="$object->asset_number" />
                @endif
                <x-pdf.row :label="__('models/inspection_object.year_manufacture.label')" :value="$object->year_manufacture" />
                @if ($inspectable instanceof OperatorLift)
                    <x-pdf.row :label="__('models/operator_lift.base_mount.label')" :value="$inspectable->base_mount->label()" />
                @endif
            </tbody>
        </table>
        @if ($inspectable instanceof Crane)
            @include('pdf._inspectable-crane', ['crane' => $inspectable])
        @endif
        <!-- Form groups -->
        @include('pdf._form-items', ['inspection' => $inspection])
        <!-- Test matrix -->
        @if (! empty($inspection->matrix) && ($testMatrix = $inspection->testMatrix()))
            @include($testMatrix->pdfView(), ['testMatrix' => $testMatrix, 'rows' => $inspection->testMatrixRows()])
        @endif
        <!-- Deficiencies -->
        @include('pdf._deficiencies')
    </body>
</html>
