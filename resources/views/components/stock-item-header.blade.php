@props([
    'isAdmin' => false,
    'stockItem',
])

<header {{ $attributes->class(['stockItemHeader']) }}>
    <div class="stockItemHeader__top">
        <img alt="" class="stockItemHeader__logo" src="{{ $this->stockItem->machine->logo }}" />
        @if ($isAdmin && $stockItem->sticker)
            <a class="btn" href="{{ $stockItem->sticker->url }}" target="_blank">
                <x-icon icon="square-arrow-out-up-right" />
                @lang('stock_items.show.btn_view')
            </a>
        @endif
    </div>
    <h1 class="stockItemHeader__title">{{ $this->stockItem->machine->model }}</h1>
    <ul class="stockItemHeader__meta">
        <li class="stockItemHeader__metaItem">
            <img alt="" height="15" src="{{ $this->stockItem->country_code->flag() }}" width="20" />
            <span class="u-text-mono">{{ $this->stockItem->stock_id }}</span>
        </li>
        @if (!empty($this->stockItem->machine->title))
            <li class="stockItemHeader__metaItem">{{ $this->stockItem->machine->title }}</li>
        @endif
        @if (!empty($this->stockItem->serial_no))
            <li class="stockItemHeader__metaItem">S/N {{ $this->stockItem->serial_no }}</li>
        @endif
    </ul>
</header>