@if ($paginator->hasPages())
    <div class="pagination">
        <div class="pagination__flex">
            @lang('pagination.page')
            <div class="pagination__current">
                {{ $paginator->currentPage() }}
                <select x-on:change="$wire.gotoPage($event.target.value, '{{ $paginator->getPageName() }}')">
                    @foreach ($elements as $element)
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                <option value="{{ $page }}">{{ $page }}</option>
                            @endforeach
                        @endif
                    @endforeach
                </select>
            </div>
            @lang('pagination.page_of') {{ $paginator->lastPage() }}
        </div>
        @if ($paginator->onFirstPage())
            <button class="pagination__nav pagination__nav--disabled pagination__nav--prev" disabled><x-icon icon="chevron-left" /></button>
        @else
            <button
                class="pagination__nav pagination__nav--prev"
                wire:click="previousPage('{{ $paginator->getPageName() }}')"
                wire:loading.attr="disabled" 
            ><x-icon icon="chevron-left" /></button>
        @endif
        @if ($paginator->hasMorePages())
            <button
                class="pagination__nav pagination__nav--next"
                wire:click="nextPage('{{ $paginator->getPageName() }}')"
                wire:loading.attr="disabled" 
            ><x-icon icon="chevron-right" /></button>
        @else
            <button class="pagination__nav pagination__nav--disabled pagination__nav--next" disabled><x-icon icon="chevron-right" /></button>
        @endif
    </div>
@endif