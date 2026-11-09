@use('App\Enums\InspectionStatus')

@props([
    'status',
])

<span
    {{ $attributes->class([
        'label',
        'label--danger' => in_array($status, [InspectionStatus::REJECTED, InspectionStatus::CAT_A_DEFICIENCIES, InspectionStatus::CAT_B_DEFICIENCIES]),
        'label--success' => $status === InspectionStatus::APPROVED,
    ]) }}
>{{ $status->label() }}</span>
