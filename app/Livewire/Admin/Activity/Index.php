<?php

namespace App\Livewire\Admin\Activity;

use App\Models\Activity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Computed]
    public function activities(): LengthAwarePaginator
    {
        return Activity::query()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
    }

    public function render()
    {
        return view('livewire.admin.activity.index');
    }
}
