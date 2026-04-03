@props([
    'label' => null,
    'model' => null,
    'name' => null,
    'placeholder' => null,
    'withRules' => false,
])

<div
    class="field"
    x-data="{
        isPasswordVisible: false,
        togglePasswordVisibility() {
            this.isPasswordVisible = !this.isPasswordVisible;
        },
        validated: {
            length: false,
            lowercase: false,
            number: false,
            uppercase: false,
        },
        validate(e) {
            const password = e.target.value;
            this.validated.length = password.length >= 8;
            this.validated.lowercase = /[a-z]/.test(password);
            this.validated.number = /[0-9]/.test(password);
            this.validated.uppercase = /[A-Z]/.test(password);
        }
    }"
>
    @if ($label)
        <label class="field__label" for="input_{{ $name ?? str_replace('.', '_', $model) }}">
            {!! Purify::config('label')->clean($label) !!}
        </label>
    @endif
    <div class="input input--block password">
        <input
            autocomplete="new-password"
            @error($name)
                aria-description="{{ $message }}"
                aria-invalid="true"
            @enderror
            id="input_{{ $name ?? str_replace('.', '_', $model) }}"
            name="{{ $name ?? str_replace('.', '_', $model) }}"
            placeholder="{{ $placeholder }}"
            type="password"
            @if ($model)
                wire:model="{{ $model }}"
            @endif
            x-bind:type="isPasswordVisible ? 'text' : 'password'"
            x-on:keyup="validate"
            {{ $attributes }}
        >
        <button class="password__toggle" type="button" x-on:click="togglePasswordVisibility">
            <span class="password__icon" x-show="!isPasswordVisible"><x-icon icon="eye" /></span>
            <span class="password__icon" x-show="isPasswordVisible"><x-icon icon="eye-off" /></span>
        </button>
    </div>
    @if ($withRules)
        <ul class="password__rules">
            <li x-bind:class="validated.length ? 'is-validated' : ''">@lang('user.password.validation.length', ['length' => 8])</li>
            <li x-bind:class="validated.uppercase ? 'is-validated' : ''">Minimaal 1 hoofdletter</li>
            <li x-bind:class="validated.lowercase ? 'is-validated' : ''">Minimaal 1 kleine letter</li>
            <li x-bind:class="validated.number ? 'is-validated' : ''">Minimaal 1 cijfer</li>
        </ul>
    @endif
    @error($model)
        <div class="field__error">{{ $message }}</div>
    @enderror
</div>
