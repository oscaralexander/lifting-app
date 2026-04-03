@use (App\Enums\Locale)

<nav class="app__nav nav">
    <a href="#"><img alt="Van der Spek" class="nav__logo"src="/assets/img/van-der-spek-wh.svg"></a>
    <button
        class="locale"
        type="button"
        x-data="{ isExpanded: false }"
        x-on:click="isExpanded = true"
        x-on:click.outside="isExpanded = false"
    >
        <div class="locale__current locale__option">
            <img alt="" class="locale__flag" src="{{ Locale::from(app()->getLocale())->flag() }}">
            {{ Locale::from(app()->getLocale())->value }}
        </div>
        <div class="locale__dropdown" x-show="isExpanded">
            @foreach (Locale::cases() as $locale)
                @if ($locale->value !== app()->getLocale())
                    <a class="locale__option" href="{{ route('locale', $locale) }}">
                        <img alt="" class="locale__flag" src="{{ $locale->flag() }}">
                        {{ $locale->value }}
                    </a>
                @endif
            @endforeach
        </div>
    </button>
</nav>
