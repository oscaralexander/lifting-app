@use('Carbon\Carbon')

@props([
    'model' => null,
    'modelName' => null,
])

<div {{ $attributes }} class="field">
    <label class="field__label">Handtekening</label>
    <div class="signature" x-data="Signature(@entangle($model))">
        <div class="field">
            <div class="signature__box">
                <canvas class="signature__canvas" x-ref="canvas"></canvas>
                <x-btn icon="eraser" small x-on:click="clear" />
            </div>
            @error ($model)
                <div class="field__error">{{ $message }}</div>
            @enderror
        </div>
        <div class="signature__form">
            <div class="signature__name">
                <x-form.input model="{{ $modelName }}" :placeholder="__('signature.name_placeholder')" />
            </div>
            <div>{{ Carbon::now()->isoFormat('D MMM Y (HH:mm)') }}</div>
        </div>
    </div>
</div>