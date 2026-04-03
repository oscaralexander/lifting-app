@use('App\Enums\FieldType')

@props([
    'formComment' => null,
])

<div class="submission__formComment">
    {{ $formComment->comment }}
</div>