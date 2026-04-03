@use('Stevebauman\Purify\Facades\Purify')
@use('Livewire\Features\SupportFileUploads\TemporaryUploadedFile')

@props([
    'accept' => null,
    'description' => null,
    'files' => [],
    'label' => null,
    'model' => null,
    'multiple' => false,
    'name' => null,
    'required' => false,
])

@php
    $files = array_filter(is_array($files) ? $files : [$files]);
    $name = $name ?? str_replace('.', '_', $model);
    $id = Str::camel('input_' . $name);
    $model ??= $attributes->whereStartsWith('wire:model')->isNotEmpty()
        ? $attributes->whereStartsWith('wire:model')->first('') : null;
@endphp

<div
    class="field upload"
    :class="{ 'is-uploading': isUploading }"
    x-data="{
        isUploading: false,
        progress: 0,
        onUploadStart(e) {
            console.log('upload start', e.target.files[0].name);
            this.isUploading = true;
        }
    }"
    x-on:livewire-upload-cancel="isUploading = false"
    x-on:livewire-upload-error="isUploading = false"
    x-on:livewire-upload-finish="isUploading = false; progress = 0"
    x-on:livewire-upload-progress="progress = $event.detail.progress"
    x-on:livewire-upload-start="onUploadStart"
>
    @if ($label)
        <label class="field__label" for="{{ $id }}">
            {!! Purify::config('label')->clean($label) !!}
        </label>
    @endif
    @if ($description)
        <div class="field__description">
            {!! Purify::config('description')->clean($description) !!}
        </div>
    @endif
    <div class="u-stack u-stack-gap-m">
        @if ($files && is_array($files) && count($files) > 0)
            <div class="u-stack u-stack-gap-xs">
                @foreach ($files as $file)
                    @php
                        $ext = Str::of($file)->afterLast('.')->lower();
                        $icon = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? 'image' : 'file';
                    @endphp
                    <div class="upload__file">
                        <x-icon :icon="$icon" />
                        @if ($file instanceof TemporaryUploadedFile)
                            <div class="upload__fileName">{{ $file->getClientOriginalName() }}</div>
                            @if (Str::startsWith($file->getMimeType(), 'image/'))
                                <a
                                    class="upload__fileAction"
                                    href="{{ $file->temporaryUrl() }}"
                                    download="{{ $file->getClientOriginalName() }}"
                                ><x-icon icon="download" /></a>
                            @endif
                            <button
                                class="upload__fileAction"
                                wire:click="deleteUpload('{{ $model }}', '{{ $file }}')"
                                wire:confirm.prevent="@lang('ui.delete_confirm')"
                            ><x-icon icon="trash" /></button>
                        @else
                            <div class="upload__fileName">{{ basename($file) }}</div>
                            <a
                                class="upload__fileAction"
                                wire:click="download('{{ $file }}')"
                            ><x-icon icon="download" /></a>
                            <button
                                class="upload__fileAction"
                                wire:click="deleteUpload('{{ $model }}', '{{ $file }}')"
                                wire:confirm.prevent="@lang('ui.delete_confirm')"
                            ><x-icon icon="trash" /></button>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="actions">
                <button
                    class="btn btn--progress"
                    :style="{ '--progress': progress }"
                    type="button"
                    x-bind:disabled="isUploading"
                >
                    <span class="btn__uploadLabel" x-bind:class="isUploading ? 'is-hidden' : ''">@lang('ui.browse')</span>
                    <span class="btn__uploadProgress" x-show="isUploading" x-text="`${progress}%`"></span>
                    <input
                        @if ($accept)
                            accept="{{ $accept }}"
                        @endif
                        @if ($multiple)
                            multiple
                        @endif
                        @if($required)
                            required
                        @endif
                        type="file"
                        @if ($model)
                            wire:model="{{ $model }}"
                        @endif
                        x-bind:disabled="isUploading"
                    >
                </button>
            </div>
        @endif
    </div>
    @if($model)
        @error ($model . '.*')
            <div class="field__error">{{ $message }}</div>
        @enderror
    @endif
</div>
