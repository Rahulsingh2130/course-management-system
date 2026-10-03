<?php

namespace App\Http\Livewire;

use App\Models\Enquiry;
use Livewire\Component;
use Livewire\WithPagination;

class EnquiryManager extends Component
{
    use WithPagination;

    public string $filterStatus = '';
    public string $filterType = '';

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFilterType(): void
    {
        $this->resetPage();
    }

    public function setStatus(int $id, string $status): void
    {
        if (in_array($status, ['new', 'contacted', 'closed'], true)) {
            Enquiry::findOrFail($id)->update(['status' => $status]);
        }
    }

    public function delete(int $id): void
    {
        Enquiry::findOrFail($id)->delete();
        session()->flash('message', 'Enquiry deleted.');
    }

    public function render()
    {
        return view('livewire.enquiry-manager', [
            'enquiries' => Enquiry::with('course')
                ->when($this->filterStatus, fn ($q, $s) => $q->where('status', $s))
                ->when($this->filterType, fn ($q, $t) => $q->where('type', $t))
                ->latest()
                ->paginate(15),
        ]);
    }
}
