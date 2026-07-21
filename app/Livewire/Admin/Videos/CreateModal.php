<?php

namespace App\Livewire\Admin\Videos;

use App\Constants\Event;
use App\Enums\ActivityType;
use App\Livewire\Admin\StockItems\Show;
use App\Models\Activity;
use App\Models\Machine;
use App\Models\StockItem;
use App\Models\Video;
use Embed\Embed;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class CreateModal extends ModalComponent
{
    /**
     * Component properties
     */
    #[Locked]
    public ?int $id = null;

    public bool $isUniversal = true;

    #[Locked]
    public int $stockItemId;

    /**
     * Model properties
     */
    public string $image_url;

    public string $title;

    public string $url;

    public ?string $video_id = null;

    /**
     * Computed properties
     */
    #[Computed]
    public function video(): Video
    {
        return $this->id
            ? Video::findOrFail($this->id)
            : new Video;
    }

    #[Computed]
    public function stockItem(): StockItem
    {
        return $this->stockItemId
            ? StockItem::findOrFail($this->stockItemId)
            : new StockItem;
    }

    public function render(): View
    {
        return view('livewire.admin.videos.create-modal');
    }

    public function rules(): array
    {
        return [
            'image_url' => ['required', 'url'],
            'title' => ['required', 'string'],
            'video_id' => ['required', 'string'],
        ];
    }

    public function updatedUrl(): void
    {
        $video_id = null;

        // Get Video ID from URL
        if (Str::of($this->url)->contains('youtube.com')) {
            $query = parse_url($this->url, PHP_URL_QUERY);
            parse_str($query, $queryParams);

            if (! empty($queryParams['v'])) {
                $video_id = $queryParams['v'];
            }
        }

        if (Str::of($this->url)->contains('youtu.be')) {
            $video_id = Str::of($this->url)->after('youtu.be/')->before('?')->toString();
        }

        if (! $video_id) {
            $this->addError('url', __('videos.modal.error_url'));

            return;
        }

        // Fetch video data from YouTube
        $embed = new Embed;
        $info = $embed->get($this->url);
        $this->image_url = $info->image;
        $this->title = $info->title;
        $this->video_id = $video_id;
    }

    public function submit(): void
    {
        $this->validate();

        $this->video->image_url = $this->image_url;
        $this->video->title = $this->title;
        $this->video->video_id = $this->video_id;
        $this->video->videoable_id = $this->isUniversal ? $this->stockItem->machine->id : $this->stockItem->id;
        $this->video->videoable_type = $this->isUniversal ? Machine::class : StockItem::class;
        $this->video->save();

        // Log activity
        Activity::log(ActivityType::VIDEO_CREATED, [
            'title' => $this->title,
            'stock_id' => $this->stockItem->stock_id,
        ]);

        $this->closeModalWithEvents([
            Show::class => Event::REFRESH,
        ]);
    }
}
