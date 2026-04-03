<?php

namespace App\Livewire\App\Submission;

use App\Models\Submission;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Show extends Component
{
    public string $hash;

    public string $stickerHash;

    public function mount(string $stickerHash, string $hash)
    {
        $this->stickerHash = $stickerHash;
        $this->hash = $hash;
    }

    #[Computed]
    public function submission(): Submission
    {
        return Submission::query()
            ->with(
                'form',
                'form.fields',
                'form.formComments',
                'form.fieldGroups.fields',
                'form.fieldGroups.formComments',
                'stockItem',
                'stockItem.machine',
                'stockItem.sticker',
                'user',
            )
            ->whereHash($this->hash)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.app.submission.show');
    }
}
