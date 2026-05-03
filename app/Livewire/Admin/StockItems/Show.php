<?php

namespace App\Livewire\Admin\StockItems;

use App\Constants\Event;
use App\Enums\ActivityType;
use App\Livewire\Admin\Documents\CreateModal;
use App\Models\Activity;
use App\Models\Document;
use App\Models\StockItem;
use App\Models\Submission;
use App\Models\Video;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Show extends Component
{
    public ?string $stockId = null;

    public function deleteDocument(int $documentId): void
    {
        $document = Document::findOrFail($documentId);
        $document->delete();

        // Log activity
        Activity::log(ActivityType::DOCUMENT_DELETED, [
            'type' => $document->type,
            'stock_id' => $this->stockItem->stock_id,
        ]);

        $this->dispatch(Event::TOAST, message: __('documents.toast.deleted'), type: 'success');
    }

    public function deleteVideo(int $videoId): void
    {
        $video = Video::findOrFail($videoId);
        $video->delete();

        // Log activity
        Activity::log(ActivityType::VIDEO_DELETED, [
            'title' => $video->title,
            'stock_id' => $this->stockItem->stock_id,
        ]);

        $this->dispatch(Event::TOAST, message: __('videos.toast.deleted'), type: 'success');
    }

    public function downloadDocument(int $documentId): StreamedResponse
    {
        $document = Document::findOrFail($documentId);

        return Storage::download($document->path, $document->filename_orig, [
            'Content-Type' => $document->mime_type,
        ]);
    }

    #[Computed]
    public function latestSubmissionsPerForm(): Collection
    {
        return $this->stockItem->latestSubmissionsPerForm();
    }

    #[Computed]
    public function submission(): ?Submission
    {
        return $this->stockItem->latestSubmission;
    }

    #[Computed]
    public function stockItem(): StockItem
    {
        return StockItem::query()
            ->with([
                'documents',
                'import',
                'machine',
                'machine.documents',
                'sticker',
                'submissions',
            ])
            ->whereStockId($this->stockId)
            ->firstOrFail();
    }

    public function mount(string $stockId): void
    {
        $this->stockId = $stockId;
    }

    #[On(Event::REFRESH)]
    public function render()
    {
        return view('livewire.admin.stock-items.show');
    }

    #[On(Event::DOCUMENT_SAVED)]
    public function onDocumentAdded(): void
    {
        $this->dispatch(Event::TOAST, message: __('documents.toast.added'), type: 'success');
    }

    #[On(Event::SORTED_DOCUMENTS)]
    public function onSortedDocuments(array $positions): void
    {
        foreach ($positions as $position => $id) {
            Document::whereId($id)->update(['position' => $position + 1]);
        }

        $this->dispatch(Event::TOAST, message: __('documents.toast.sorted'), type: 'success');

        // Reload document relations
        $this->stockItem->load('documents', 'machine', 'machine.documents');
    }

    #[On(Event::SORTED_VIDEOS)]
    public function onSortedVideos(array $positions): void
    {
        foreach ($positions as $position => $id) {
            Video::whereId($id)->update(['position' => $position + 1]);
        }

        $this->dispatch(Event::TOAST, message: __('videos.toast.sorted'), type: 'success');

        // Reload video relations
        $this->stockItem->load('videos');
    }

    public function openDocumentModal(): void
    {
        $this->dispatch(
            'openModal',
            component: CreateModal::class,
            arguments: [
                'stockItemId' => $this->stockItem->id,
            ]
        );
    }

    public function openVideoModal(): void
    {
        $this->dispatch(
            'openModal',
            component: \App\Livewire\Admin\Videos\CreateModal::class,
            arguments: [
                'stockItemId' => $this->stockItem->id,
            ]
        );
    }
}
