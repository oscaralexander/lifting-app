<?php

namespace App\Livewire\App\QR;

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Sticker;
use App\Models\StockItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Link extends Component
{
    use WithPagination;

    public string $hash;

    public string $search = '';

    public Sticker $sticker;

    public function link(int $stockItemId)
    {
        $stockItem = StockItem::with('sticker')->find($stockItemId);

        $this->sticker->update([
            'last_scanned_at' => now(),
            'linked_at' => now(),
            'stock_item_id' => $stockItemId,
        ]);

        Activity::log(ActivityType::STICKER_LINKED, [
            'sticker_hash' => $this->sticker->hash,
            'stock_id' => $stockItem->stock_id,
        ]);

        session()->flash('success', __('qr.link.flash_success'));
        return redirect()->route('qr.show', ['hash' => $this->hash]);
    }

    public function mount(string $hash)
    {
        $this->hash = $hash;
        $this->sticker = Sticker::where('hash', $hash)->firstOrFail();

        if ($this->sticker->stock_item_id) {
            return redirect()->route('qr.show', ['hash' => $this->hash]);
        }
    }

    public function render()
    {
        return view('livewire.app.qr.link');
    }

    #[Computed]
    public function stockItems(): LengthAwarePaginator
    {
        return StockItem::with(['machine' => fn ($machine) => $machine->withCount('documents')])
            ->whereDoesntHave('sticker')
            ->when($this->search, function ($query) {
                return $query->where('frame_no', 'like', '%' . $this->search . '%')
                    ->orWhere('license_plate_no', 'like', '%' . $this->search . '%')
                    ->orWhere('stock_id', 'like', '%' . $this->search . '%')
                    ->orWhere('serial_no', 'like', '%' . $this->search . '%');
            })
            ->paginate(25);
    }
}
