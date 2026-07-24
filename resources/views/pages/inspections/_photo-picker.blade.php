@if ($this->inspection->exists)
@php
    $outsmartPhotos = collect($this->inspection->outsmart_photos ?? [])
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
            title: '',
            selected: [],
            show(detail) {
                this.fieldKey = detail.fieldKey;
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
                const index = this.selected.indexOf(url);

                if (index === -1) {
                    this.selected.push(url);
                } else {
                    this.selected.splice(index, 1);
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
                @if ($outsmartPhotos->isNotEmpty())
                    <div class="inspection__photos">
                        @foreach ($outsmartPhotos as $photo)
                            <figure
                                class="inspection__photos-item photoPicker__photo"
                                wire:key="picker-{{ $loop->index }}"
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
            </footer>
        </div>
    </div>
</template>
@endif