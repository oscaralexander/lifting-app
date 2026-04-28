@props([
    'inspectionObject' => null,
])

@if ($inspectionObject)
    <div>
        @if ($inspectionObject->manufacturer)
            <x-data-item :label="__('models/inspection_object.manufacturer.label')" :value="$inspectionObject->manufacturer" />
        @endif
        @if ($inspectionObject->model)
            <x-data-item :label="__('models/inspection_object.model.label')" :value="$inspectionObject->model" />
        @endif
        @if ($inspectionObject->serial_number)
            <x-data-item :label="__('models/inspection_object.serial_number.label')" :value="$inspectionObject->serial_number" />
        @endif
        @if ($inspectionObject->asset_number)
            <x-data-item :label="__('models/inspection_object.asset_number.label')" :value="$inspectionObject->asset_number" />
        @endif
        @if ($inspectionObject->year_manufacture)
            <x-data-item :label="__('models/inspection_object.year_manufacture.label')" :value="$inspectionObject->year_manufacture" />
        @endif
    </div>
@endif