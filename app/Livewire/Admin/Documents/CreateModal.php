<?php

namespace App\Livewire\Admin\Documents;

use App\Constants\Event;
use App\Enums\ActivityType;
use App\Enums\DocumentType;
use App\Livewire\Admin\StockItems\Show;
use App\Models\Activity;
use App\Models\Document;
use App\Models\Machine;
use App\Models\StockItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Spatie\PdfToImage\Pdf;

class CreateModal extends ModalComponent
{
    use WithFileUploads;

    public array $files;

    public bool $isUniversal = true;

    public int $stockItemId;

    public string $type;

    #[Computed]
    public function fileTypes(): array
    {
        foreach (DocumentType::cases() as $type) {
            $options[$type->value] = $type->label();
        }

        return $options;
    }

    public function mount(int $stockItemId): void
    {
        $this->stockItemId = $stockItemId;
    }

    #[Computed]
    public function stockItem(): StockItem
    {
        return $this->stockItemId
            ? StockItem::with('machine')->findOrFail($this->stockItemId)
            : new StockItem;
    }

    public function render(): View
    {
        return view('livewire.admin.document.create-modal');
    }

    public function rules(): array
    {
        return [
            'files.*' => ['file', 'max:262144', 'mimes:pdf'], // 256MB
            'type' => ['required', Rule::enum(DocumentType::class)],
        ];
    }

    public function submit(): void
    {
        $data = $this->validate();
        $stockItem = StockItem::with('machine')->findOrFail($this->stockItemId);
        $subfolder = $data['type'];

        foreach ($this->files as $file) {
            if ($file instanceof TemporaryUploadedFile) {
                $extension = $file->getClientOriginalExtension();
                $filename = Document::getUniqueFilename($extension);

                // Create thumbnail from PDF
                if ($file->getMimeType() === 'application/pdf') {
                    $thumbnailPath = Storage::disk('public')->path(env('APP_PATH_THUMBNAILS').'/'.$subfolder.'/'.Str::of($filename)->replaceEnd($extension, 'jpg'));

                    try {
                        $pdf = new Pdf($file->getRealPath());
                        $pdf->thumbnailSize(640)->quality(90)->save($thumbnailPath);
                    } catch (\Exception $e) {
                        // Waarschijnlijk geen Ghostscript geïnstalleerd
                    }
                }

                // Store the file
                if ($data['type'] === DocumentType::MANUAL->value) {
                    // Store manuals in public folder for quick access
                    $path = env('APP_PATH_MANUALS');
                    $file->storeAs($path, $filename, 'public');
                    $data['documentable_id'] = $this->isUniversal ? $this->stockItem->machine->id : $this->stockItem->id;
                    $data['documentable_type'] = $this->isUniversal ? Machine::class : StockItem::class;
                } else {
                    // Store other files in private folder
                    $path = env('APP_PATH_DOCUMENTS').'/'.$subfolder;
                    $file->storeAs($path, $filename);
                    $data['documentable_id'] = $this->stockItem->id;
                    $data['documentable_type'] = StockItem::class;
                }

                // Store file in database
                $document = Document::create([
                    ...$data,
                    'filename' => $filename,
                    'filename_orig' => $file->getClientOriginalName(),
                    'filesize' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        // Log activity
        Activity::log(ActivityType::DOCUMENT_CREATED, [
            'type' => $data['type'],
            'stock_id' => $stockItem->stock_id,
        ]);

        $this->closeModalWithEvents([
            Show::class => [Event::DOCUMENT_SAVED, [$document->id]],
        ]);
    }
}
