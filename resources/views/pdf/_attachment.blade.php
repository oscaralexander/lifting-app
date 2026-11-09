@use('App\Lib\InspectionResult')

@php
    $attachment = $inspection->inspectionObject;
@endphp
<!-- Attachment -->
<div class="page-break-before">
    <table class="table">
        <thead>
            <tr>
                <th colspan="2" scope="col">Verwisselbaar hulpstuk: {{ $attachment?->type_label }}</th>
            </tr>
        </thead>
        <tbody>
            <x-pdf.row :label="__('models/inspection_object.manufacturer.label')" :value="$attachment?->manufacturer" />
            <x-pdf.row :label="__('models/inspection_object.model.label')" :value="$attachment?->model" />
            <x-pdf.row :label="__('models/inspection_object.serial_number.label')" :value="$attachment?->serial_number" />
            <x-pdf.row :label="__('models/inspection_object.asset_number.label')" :value="$attachment?->asset_number" />
            <x-pdf.row :label="__('models/inspection_object.year_manufacture.label')" :value="$attachment?->year_manufacture" />
            <x-pdf.row label="Keuringsschema" :value="$inspection->form->name" />
            <x-pdf.row label="Bevindingen" :value="InspectionResult::html($inspection)" />
        </tbody>
    </table>
    @include('pdf._form-items', ['inspection' => $inspection])
</div>
@include('pdf._deficiencies', [
    'inspection' => $inspection,
    'deficienciesHeading' => 'Tekortkomingen '.mb_strtolower($attachment?->type_label ?? ''),
])
