<?php

namespace App\Livewire\Admin\StickerBatches;

use App\Constants\Event;
use App\Models\StickerBatch;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;

class CreateModal extends ModalComponent
{
    #[Validate('required|integer|min:1|max:1000')]
    public int $count = 100;

    public function render(): View
    {
        return view('livewire.admin.sticker-batches.create-modal');
    }

    public function submit(): void
    {
        $data = $this->validate();

        $stickerBatch = StickerBatch::create([
            ...$data,
            'filename' => now()->format('Y-m-d-H-i-s').'.zip',
        ]);

        $this->closeModalWithEvents([
            Index::class => [Event::STICKER_BATCH_SAVED, ['id' => $stickerBatch->id]],
        ]);
    }
}
