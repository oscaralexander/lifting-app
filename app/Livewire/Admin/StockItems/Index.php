<?php

namespace App\Livewire\Admin\StockItems;

use App\Constants\Event;
use App\Enums\DocumentType;
use App\Models\Sticker;
use App\Models\StockItem;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Index extends Component
{
    use WithPagination;

    public ?string $search = null;

    #[Url(as: 'stickered', except: null)]
    public bool $isFilterStickered = false;

    #[Url(as: 'id', except: null)]
    public ?string $stockId = null;

    public function downloadSticker(int $stickerId): StreamedResponse
    {
        $sticker = Sticker::with('stockItem')->findOrFail($stickerId);

        return Storage::download($sticker->path, $sticker->stockItem->stock_id.'.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function mount(?string $stockId = null): void
    {
        $this->stockId = $stockId;
    }

    #[On(Event::DOCUMENT_SAVED)]
    public function render()
    {
        return view('livewire.admin.stock-items.index');
    }

    public function show(string $stockId): void
    {
        $this->stockId = $stockId;
        $this->dispatch(Event::SHOW_SLIDE_OVER);
    }

    #[Computed]
    public function stockItems(): LengthAwarePaginator
    {
        return StockItem::with([
            'machine' => fn ($machine) => $machine->withCount('documents', 'videos'),
            'sticker',
        ])
            ->when($this->isFilterStickered, function ($query) {
                return $query->whereHas('sticker');
            })
            ->when($this->search, function ($query) {
                return $query->where(function ($query) {
                    $query->where('stock_id', 'like', '%'.$this->search.'%')
                        ->orWhere('serial_no', 'like', '%'.$this->search.'%')
                        ->orWhereHas('machine', function ($query) {
                            $query->where('name', 'like', '%'.$this->search.'%')
                                ->orWhere('model', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->withCount(['documents as documents_count' => fn ($query) => $query->where('type', '!=', DocumentType::MANUAL)])
            ->withCount(['documents as manuals_count' => fn ($query) => $query->where('type', DocumentType::MANUAL)])
            ->withCount('videos')
            ->orderBy('id', 'desc')
            ->paginate(50);
    }

    public function updated(): void
    {
        $this->resetPage();
    }
}
