<table class="details">
    <tbody>
        @if ($this->stockItem->serial_no)
            <tr>
                <th scope="row">@lang('stock_item.serial_no')</th>
                <td><span class="u-text-mono">{{ $this->stockItem->serial_no }}</span></td>
            </tr>
        @endif
        @if ($this->stockItem->barcode)
            <tr>
                <th scope="row">@lang('stock_item.barcode')</th>
                <td><span class="u-text-mono">{{ $this->stockItem->barcode }}</span></td>
            </tr>
        @endif
        @if ($this->stockItem->frame_no)
            <tr>
                <th scope="row">@lang('stock_item.frame_no')</th>
                <td><span class="u-text-mono">{{ $this->stockItem->frame_no }}</span></td>
            </tr>
        @endif
        @if ($this->stockItem->license_plate_no)
            <tr>
                <th scope="row">@lang('stock_item.license_plate_no')</th>
                <td><span class="u-text-mono">{{ $this->stockItem->license_plate_no }}</span></td>
            </tr>
        @endif
        @if ($this->stockItem->sticker)
            <tr>
                <th scope="row">@lang('stock_item.sticker_hash')</th>
                <td><span class="u-text-mono">{{ $this->stockItem->sticker->hash }}</span></td>
            </tr>
        @endif
    </tbody>
</table>