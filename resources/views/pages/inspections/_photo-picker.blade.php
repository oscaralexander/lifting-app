@if ($this->inspection->exists)
@php
    $photos = collect($this->inspection->outsmart_photos ?? [])
        ->merge(collect($this->inspection->uploaded_photos ?? [])->map(fn ($photo) => ['image' => $photo['image'] ?? null]))
        ->filter(fn ($photo) => ! empty($photo['image']))
        ->values();
@endphp
<template x-teleport="body">
    <div
        class="photoPicker"
        x-cloak
        x-data="{
            open: false,
            fieldKey: null,
            multiple: true,
            title: '',
            selected: [],
            show(detail) {
                this.fieldKey = detail.fieldKey;
                this.multiple = detail.multiple ?? true;
                this.title = detail.title;
                this.selected = detail.selected;
                this.open = true;
            },
            cancel() {
                this.open = false;
            },
            isSelected(url) {
                return this.selected.includes(url);
            },
            toggle(url) {
                if (! this.multiple) {
                    this.selected = this.isSelected(url) ? [] : [url];

                    return;
                }

                const index = this.selected.indexOf(url);

                if (index === -1) {
                    this.selected.push(url);
                } else {
                    this.selected.splice(index, 1);
                }
            },
            select(url) {
                if (! this.multiple) {
                    this.selected = [url];
                } else if (! this.selected.includes(url)) {
                    this.selected.push(url);
                }
            },
            save() {
                $wire.saveFieldPhotos(this.fieldKey, this.selected).then(() => {
                    this.open = false;
                });
            },
        }"
        x-on:keydown.escape.window="cancel()"
        x-on:photo-picker-open.window="show($event.detail)"
        x-on:photo-picker-photo-uploaded.window="select($event.detail.url)"
        x-show="open"
    >
        <div
            class="photoPicker__overlay"
            x-on:click="cancel()"
            x-show="open"
            x-transition:enter.opacity.duration.250ms
            x-transition:leave.opacity.duration.200ms
        ></div>
        <div
            class="photoPicker__dialog"
            x-show="open"
            x-transition:enter.opacity.scale.95.duration.250ms
            x-transition:leave.opacity.scale.95.duration.200ms
        >
            <header class="photoPicker__header">
                <h3 class="photoPicker__title" x-text="title"></h3>
                <button class="photoPicker__close" type="button" x-on:click="cancel()"><x-icon icon="x" /></button>
            </header>
            <div class="photoPicker__body">
                @if ($photos->isNotEmpty())
                    <div class="inspection__photos">
                        @foreach ($photos as $photo)
                            <figure
                                class="inspection__photos-item photoPicker__photo"
                                wire:key="picker-{{ md5($photo['image']) }}"
                                x-bind:class="{ 'is-selected': isSelected(@js($photo['image'])) }"
                                x-on:click="toggle(@js($photo['image']))"
                            >
                                <div class="inspection__photos-imgBox">
                                    <img
                                        alt="{{ $photo['title'] ?? '' }}"
                                        class="inspection__photos-img"
                                        loading="lazy"
                                        src="{{ $photo['image'] }}"
                                    />
                                    <span class="photoPicker__check"><x-icon icon="check" stroke-width="5" /></span>
                                    @if (! empty($photo['title']))
                                        <figcaption class="photoPicker__caption">{{ $photo['title'] }}</figcaption>
                                    @endif
                                </div>
                            </figure>
                        @endforeach
                    </div>
                @else
                    <div class="photoPicker__noPhotos">
                        <x-icon icon="image-off" />
                        <div class="u-stack u-stack-gap-xs">
                            <h2 class="photoPicker__noPhotos-heading">@lang('inspections.form.no_photos_heading')</h2>
                            <p class="photoPicker__noPhotos-text">
                                @if ($this->inspection->exists && $this->inspection->outsmart_work_order_id)
                                    @lang('inspections.form.no_photos_text')
                                @else
                                    @lang('inspections.form.no_photos_text_not_linked')
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            </div>
            <footer class="photoPicker__footer">
                <div class="actions">
                    <x-btn
                        primary
                        type="button"
                        wire:loading.attr="disabled"
                        wire:target="saveFieldPhotos"
                        x-on:click="save()"
                    >@lang('ui.save')</x-btn>
                    <span>
                        @lang('ui.or')
                        <x-btn text type="button" x-on:click="cancel()">@lang('ui.cancel')</x-btn>
                    </span>
                </div>
                <div class="photoPicker__upload">
                    @error('pickerPhoto')
                        <span class="photoPicker__uploadError">{{ $message }}</span>
                    @enderror
                    <x-btn
                        icon="image"
                        type="button"
                        wire:loading.attr="disabled"
                        wire:loading.class="is-loading"
                        wire:target="pickerPhoto"
                    >
                        @lang('inspections.form.upload_photo')
                        <input
                            accept="image/*"
                            type="file"
                            wire:loading.attr="disabled"
                            wire:model="pickerPhoto"
                            wire:target="pickerPhoto"
                        />
                    </x-btn>
                </div>
            </footer>
        </div>
    </div>
</template>
@endif