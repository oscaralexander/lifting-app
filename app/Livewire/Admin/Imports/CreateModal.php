<?php

namespace App\Livewire\Admin\Imports;

use App\Enums\ImportType;
use App\Models\Import;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class CreateModal extends ModalComponent
{
    use WithFileUploads;

    public array $files;

    public ?int $id = null;

    public function render(): View
    {
        return view('livewire.admin.imports.create-modal');
    }

    public function rules(): array
    {
        return [
            'files.*' => ['file', 'max:262144', 'mimes:csv'], // 256MB
        ];
    }

    public function submit(): void
    {
        $file = $this->files[0] ?? null;

        if ($file instanceof TemporaryUploadedFile) {
            $filename = $file->getClientOriginalName();
            $file->storeAs(env('APP_PATH_IMPORTS'), $filename);

            $import = Import::create([
                'filename' => $filename,
                'type' => ImportType::UPLOAD,
            ]);
        }

        $this->closeModalWithEvents([
            \App\Livewire\Admin\Imports\Index::class => ['created', ['id' => $import->id]],
        ]);
    }
}
