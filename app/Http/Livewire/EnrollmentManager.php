<?php

namespace App\Http\Livewire;

use App\Models\Enrollment;
use App\Models\Workshop;
use Livewire\Component;
use Livewire\WithPagination;

class EnrollmentManager extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $filterWorkshop = null;
    public string $filterStatus = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirm(int $id): void
    {
        Enrollment::findOrFail($id)->update(['status' => Enrollment::STATUS_CONFIRMED]);
        session()->flash('message', 'Enrollment confirmed.');
    }

    public function cancel(int $id): void
    {
        Enrollment::findOrFail($id)->update(['status' => Enrollment::STATUS_CANCELLED]);
        session()->flash('message', 'Enrollment cancelled.');
    }

    public function render()
    {
        $enrollments = Enrollment::with(['user', 'workshop.course'])
            ->when($this->search, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%")))
            ->when($this->filterWorkshop, fn ($q) => $q->where('workshop_id', $this->filterWorkshop))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest('enrolled_at')
            ->paginate(15);

        return view('livewire.enrollment-manager', [
            'enrollments' => $enrollments,
            'workshops' => Workshop::upcoming()->get(),
        ]);
    }
}
