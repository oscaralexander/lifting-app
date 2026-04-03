@props([
    'show' => false,
])

<div
    class="slideOver"
    x-cloak
    x-data="{
        show: {{ $show ? 'true' : 'false' }},
        init() {           
            this.$watch('show', (show) => {
                if (!show) {
                    this.$refs.content.addEventListener('animationend', (e) => {
                        this.$dispatch('slideOverHidden');
                    }, { once: true });
                }
            });
        },
    }"
    x-on:close.stop="show = false"
    x-on:hide-slide-over.window="show = false"
    x-on:show-slide-over.window="show = true"
    x-on:keydown.escape.window="show = false"
    x-show="show"
>
    <div
        class="slideOver__overlay"
        x-show="show"
        x-on:click="show = false"
        x-transition:enter="is-enter"
        x-transition:enter-start="is-enter-start"
        x-transition:enter-end="is-enter-end"
        x-transition:leave="is-leave"
        x-transition:leave-start="is-leave-start"
        x-transition:leave-end="is-leave-end"
    ></div>
    <div
        class="slideOver__content"
        x-show="show"
        x-transition:enter="is-enter"
        x-transition:enter-start="is-enter-start"
        x-transition:enter-end="is-enter-end"
        x-transition:leave="is-leave"
        x-transition:leave-start="is-leave-start"
        x-transition:leave-end="is-leave-end"
        x-ref="content"
    >
        {{ $slot }}
    </div>
</div>