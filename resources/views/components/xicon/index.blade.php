@props([
    'icon' => null,
])

<x-dynamic-component :component="'icon.' . $icon" />
