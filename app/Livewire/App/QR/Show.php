<?php

namespace App\Livewire\App\QR;

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Settings;
use App\Models\Sticker;
use App\Models\StockItem;
use App\Models\Submission;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    public string $hash;

    #[Locked]
    public bool $isAuthenticated = false;

    public string $passcode = '';

    public ?Sticker $sticker;

    public string $service_no;

    private function attemptAuth($passcode): bool
    {
        return Settings::where([
            'key' => 'passcode',
            'value' => $passcode,
        ])->exists();
    }

    public function authenticate(): void
    {
        $passcode = $this->pull('passcode');

        if ($this->attemptAuth($passcode)) {
            $this->isAuthenticated = true;
            session()->put('passcode', $passcode);

            return;
        }

        $this->addError('passcode', __('qr.show.authenticate_error'));
    }

    #[Computed]
    public function submission(): ?Submission
    {
        $submission = $this->stockItem->latestSubmission;

        if ($submission) {
            $submission->load('form.fields', 'user');
        }

        return $submission;
    }

    #[Computed]
    public function stockItem(): StockItem
    {
        return $this->sticker->stockItem;
    }

    public function download(int $documentId)
    {
        return $this->stockItem->documents()->find($documentId)->download();
    }

    public function mount(string $hash)
    {
        $this->sticker = Sticker::query()
            ->with(
                'stockItem',
                'stockItem.documents',
                'stockItem.machine',
                'stockItem.machine.documents',
            )
            ->firstWhere('hash', $hash);

        if (! $this->sticker || ! $this->sticker->stockItem) {
            return redirect()->route('qr.link', ['hash' => $hash]);
        }

        Activity::log(ActivityType::STICKER_SCANNED, [
            'sticker_hash' => $this->sticker->hash,
            'stock_id' => $this->stockItem->stock_id,
        ]);

        // Get service number for stock item's country code
        $countryCode = $this->sticker->stockItem->country_code->value;
        $this->service_no = Settings::where('key', 'service_no_'.$countryCode)->first()->value;

        // Authenticate viewer
        $this->isAuthenticated = auth('web')->check();

        if (! $this->isAuthenticated) {
            if ($passcode = session()->get('passcode')) {
                $this->isAuthenticated = $this->attemptAuth($passcode);
            }
        }
    }

    public function render()
    {
        return view('livewire.app.qr.show');
    }
}
