<div class="user" x-data="{ isPopoutOpen: false }">
    <div class="user__current" x-on:click="isPopoutOpen = !isPopoutOpen">
        <div class="user__image">
            <x-avatar initials="{{ auth()->user()->initials }}" src="{{ auth()->user()->avatar }}" />
        </div>
        <div class="user__info">
            <div class="user__name">{{ auth()->user()->name }}</div>
            <div class="user__company">{{ auth()->user()->email }}</div>
        </div>
    </div>
    <div
        class="user__popout"
        x-on:click.outside="isPopoutOpen = false"
        x-show="isPopoutOpen"
        x-transition:enter.duration.125ms="is-enter"
        x-transition:leave.duration.125ms="is-leave"
    >
        <ul class="user__menu">
            <li class="user__menuItem">
                <a
                    class="user__menuLink"
                    href="{{ route('users.edit', auth()->user()->id) }}"
                    wire:navigate
                >
                    <x-icon icon="user" />
                    <span class="user__menuLabel">{{ __('nav.user.settings') }}</span>
                </a>
            </li>
            <li class="user__menuItem">
                <form action="{{ route('auth.logout') }}" method="post">
                    @csrf
                    <button class="user__menuLink" type="submit">
                        <x-icon icon="log-out" />
                        <span class="user__menuLabel">{{ __('auth.sign_out') }}</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>