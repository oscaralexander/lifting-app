<div>
    <x-modal.header>{{ $clientName }} — @lang('clients.modal.work_orders_heading')</x-modal.header>
    <x-modal.body>
        <div wire:init="load" class="u-stack u-stack-gap-l">
            @if (! $loaded)
                <p class="u-text-lc">@lang('clients.modal.loading')</p>
            @elseif (! $debtorNumber)
                <p class="u-text-lc">@lang('clients.modal.no_debtor')</p>
            @elseif (empty($workOrders))
                <p class="u-text-lc">@lang('clients.modal.no_work_orders')</p>
            @else
                <div class="u-stack u-stack-gap-l">
                    @foreach ($workOrders as $workOrder)
                        {{-- 
                        <div wire:key="wo-{{ $loop->index }}" class="u-stack u-stack-gap-xs">
                            @foreach ($workOrder as $key => $value)
                                @if (! is_array($value) && filled($value))
                                    <x-data-item :label="$key" :value="$value" />
                                @endif
                            @endforeach
                        </div>
                        --}}
                        <div>{{ $workOrder['OrderNr'] }}</div>
                        <div>{{ $workOrder['TypeOfWork'] }}</div>
                        <div>{{ $workOrder['WorkDescription'] }}</div>
                        <div>{{ $workOrder['CreationDate'] }}</div>
                        <div>{{ $workOrder['status'] }}</div>
                        @if ($workOrder['PdfUrl'])
                            <a href="{{ $workOrder['PdfUrl'] }}" target="_blank">Download PDF</a>
                        @endif
                    @endforeach
                </div>
            @endif

            <div class="actions">
                <x-btn text wire:click="$dispatch('closeModal')">@lang('ui.close')</x-btn>
            </div>
        </div>
    </x-modal.body>
</div>
