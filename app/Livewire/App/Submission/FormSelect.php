<?php

namespace App\Livewire\App\Submission;

use Illuminate\Support\Facades\DB;
use App\Enums\FormType;
use App\Models\Form;
use App\Models\Sticker;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class FormSelect extends Component
{
    public ?int $formId = null;

    public $formType = FormType::BASELINE_INSPECTION->value;

    public string $stickerHash;

    #[Computed]
    public function forms(): Collection
    {
        return Form::query()
            ->where('type', $this->formType)
            ->orderBy('name_' . app()->getLocale())
            ->get();
    }

    #[Computed]
    public function filteredForms(): Collection
    {
        return $this->forms()->where('type', $this->formType);
    }

    public function mount(string $stickerHash)
    {
        $this->stickerHash = $stickerHash;
    }

    #[Computed]
    public function sticker(): Sticker
    {
        return Sticker::query()
            ->with(
                'stockItem',
                'stockItem.machine',
            )
            ->whereHash($this->stickerHash)
            ->firstOrFail();
    }

    public function continue()
    {
        return $this->redirect(route('submission.form', [
            'formId' => $this->formId,
            'stickerHash' => $this->stickerHash,
        ]), navigate: true);
    }

    public function render()
    {
        return view('livewire.app.submission.form-select');
    }

    public function updatedFormType() {
        $this->formId = null;
    }
}
