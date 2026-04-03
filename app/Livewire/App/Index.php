<?php

namespace App\Livewire\App;

use App\Models\Sticker;
use Livewire\Component;

class Index extends Component
{
    public string $hash = '';

    public function submit(): void
    {
        $sticker = Sticker::where('hash', $this->hash)->first();

        if (!$sticker) {
            $this->addError('hash', __('app.index.hash_invalid'));
            return;
        }

        if (is_null($sticker->stock_item_id)) {
            // Sticker is not linked to a stock item yet
            $this->redirect(route('qr.link', $this->hash), true);
        }

        // Sticker is linked
        $this->redirect(route('qr.show', $this->hash), true);
    }

    public function render()
    {
        return view('livewire.app.index');
    }
}
