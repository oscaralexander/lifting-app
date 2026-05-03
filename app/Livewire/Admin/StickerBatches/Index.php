<?php

namespace App\Livewire\Admin\StickerBatches;

use App\Constants\Event;
use App\Jobs\GenerateStickers;
use App\Models\StickerBatch;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    #[On(Event::STICKER_BATCH_SAVED)]
    public function onSaved(int $id): void
    {
        $stickerBatch = StickerBatch::findOrFail($id);

        // Dispatch job to generate stickers
        GenerateStickers::dispatch($stickerBatch); // dispatchSync()
    }

    public function openCreateModal(): void
    {
        $this->dispatch('openModal', component: CreateModal::class);
    }

    public function render(): View
    {
        return view('livewire.admin.sticker-batches.index');
    }

    #[Computed]
    public function stickerBatches(): Collection
    {
        return StickerBatch::orderBy('id', 'desc')->get();
    }
}
