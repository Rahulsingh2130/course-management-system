<?php

namespace App\Http\Livewire;

use App\Models\Course;
use App\Models\User;
use App\Models\Workshop;
use Livewire\Component;
use Livewire\WithPagination;

class WorkshopScheduler extends Component
{
    use WithPagination;

    public ?int $editingId = null;
    public ?int $course_id = null;
    public ?int $instructor_id = null;
    public string $batch_name = '';
    public string $starts_at = '';
    public string $ends_at = '';
    public int $seat_limit = 30;

    public bool $showForm = false;

    protected function rules(): array
    {
        return [
            'course_id' => 'required|exists:courses,id',
            'instructor_id' => 'nullable|exists:users,id',
            'batch_name' => 'required|string|max:255',
            'starts_at' => 'required|date|after_or_equal:today',
            'ends_at' => 'required|date|after:starts_at',
            'seat_limit' => 'required|integer|min:1',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            Workshop::findOrFail($this->editingId)->update($validated);
            session()->flash('message', 'Batch schedule updated.');
        } else {
            Workshop::create($validated);
            session()->flash('message', 'New batch scheduled successfully.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function edit(int $id): void
    {
        $workshop = Workshop::findOrFail($id);
        $this->editingId = $workshop->id;
        $this->course_id = $workshop->course_id;
        $this->instructor_id = $workshop->instructor_id;
        $this->batch_name = $workshop->batch_name;
        $this->starts_at = $workshop->starts_at->format('Y-m-d\TH:i');
        $this->ends_at = $workshop->ends_at->format('Y-m-d\TH:i');
        $this->seat_limit = $workshop->seat_limit;
        $this->showForm = true;
    }

    public function delete(int $id): void
    {
        Workshop::findOrFail($id)->delete();
        session()->flash('message', 'Batch removed from schedule.');
    }

    protected function resetForm(): void
    {
        $this->reset(['editingId', 'course_id', 'instructor_id', 'batch_name', 'starts_at', 'ends_at']);
        $this->seat_limit = 30;
    }

    public function render()
    {
        return view('livewire.workshop-scheduler', [
            'workshops' => Workshop::with(['course', 'instructor'])->latest('starts_at')->paginate(10),
            'courses' => Course::where('is_active', true)->get(),
            'instructors' => User::where('role', User::ROLE_INSTRUCTOR)->get(),
        ]);
    }
}
