<?php

namespace App\Jobs;

use App\Lib\QR;
use App\Models\Sticker;
use App\Models\StickerBatch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GenerateStickers implements ShouldQueue
{
    use Queueable;

    public StickerBatch $stickerBatch;

    public $timeout = 120;

    public function __construct(StickerBatch $stickerBatch)
    {
        $this->stickerBatch = $stickerBatch;
    }

    public function handle(): void
    {
        $paths = [];

        for ($i = 0; $i < $this->stickerBatch->count; $i++) {
            // Create new sticker
            $sticker = new Sticker();
            $sticker->sticker_batch_id = $this->stickerBatch->id;
            $sticker->save();

            // Generate sticker PDF
            $paths[] = QR::makeSticker($sticker);
        }

        // Zip stickers
        $zip = new ZipArchive();
        $zip->open(Storage::disk('public')->path($this->stickerBatch->path), ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFile(resource_path('stickers/_cutcontour.pdf'), '_cutcontour.pdf');
        $zip->addFile(resource_path('stickers/JetBrainsMono-Bold.ttf'), '_fonts/JetBrainsMono-Bold.ttf');

        foreach ($paths as $path) {
            $zip->addFile($path, basename($path));
        }

        $zip->close();

        // Delete individual sticker PDFs
        foreach ($paths as $path) {
            unlink($path);
        }

        $this->stickerBatch->update([
            'processed_at' => now(),
        ]);
    }
}
