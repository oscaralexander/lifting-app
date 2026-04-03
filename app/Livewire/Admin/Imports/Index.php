<?php

namespace App\Livewire\Admin\Imports;

use App\Jobs\ImportCSV;
use App\Models\Import;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function download(int $id): BinaryFileResponse
    {
        $import = Import::findOrFail($id);
        return response()->download(Storage::path($import->path), $import->filename);
    }

    #[Computed]
    public function imports(): LengthAwarePaginator
    {
        return Import::orderBy('created_at', 'desc')->paginate(25);
    }

    #[On('created')]
    public function onCreated(int $id): void
    {
        $import = Import::findOrFail($id);

        // Dispatch job to import CSV
        (new ImportCSV($import))->handle();
    }

    public function openCreateModal(): void
    {
        $this->dispatch('openModal', component: CreateModal::class);
    }

    public function render()
    {
        return view('livewire.admin.imports.index');
    }
}
