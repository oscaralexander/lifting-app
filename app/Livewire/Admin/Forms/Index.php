<?php

namespace App\Livewire\Admin\Forms;

use App\Constants\Event;
use App\Models\Form;
use App\Models\FieldForm;
use App\Models\FieldGroup;
use App\Models\FormComment;
use App\Models\Submission;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function delete(int $id)
    {
        DB::table('form_stock_item')->where('form_id', $id)->delete();
        FieldForm::where('form_id', $id)->delete();
        FieldGroup::where('form_id', $id)->delete();
        FormComment::where('form_id', $id)->delete();
        Submission::where('form_id', $id)->delete();
        Form::where('id', $id)->delete();

        $this->dispatch(Event::TOAST, message: __('forms.toast.deleted'), type: 'success');
    }

    #[Computed]
    public function forms(): LengthAwarePaginator
    {
        return Form::withCount('fields')->orderBy('created_at', 'desc')->paginate(25);
    }

    #[On(Event::REFRESH)]
    public function render()
    {
        return view('livewire.admin.forms.index');
    }
}
