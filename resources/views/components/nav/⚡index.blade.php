<?php

use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[On('user-updated')]
    public function render()
    {
        return $this->view();
    }
}
?>

<nav
    class="admin__nav nav"
    id="nav"
    x-bind:class="{
        'is-expanded': isExpanded,
    }"
    x-data="{
        isExpanded: false,
    }"
>
    <div class="nav__flex">
        <a class="nav__logo" href="{{ route('admin') }}" wire:navigate>
            <img alt="Lifting Inspections" src="/assets/img/lifting-inspections-fc.svg" />
        </a>
    </div>
    <x-nav.user />
    <button class="nav__toggle" x-on:click.stop="isExpanded = !isExpanded"><div></div></button>
    <div class="nav__overlay" x-on:click="isExpanded = false"></div>
    <div class="nav__drawer" x-on:click.outside="isExpanded = false">
        <a class="nav__logo" href="{{ route('admin') }}" wire:navigate>
            <img alt="Lifting Inspections" src="/assets/img/lifting-inspections-fc.svg" />
        </a>
        <x-nav.main />
    </div>
</nav>