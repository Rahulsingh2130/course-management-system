<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CourseManager extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $filterCategory = null;

    public ?int $editingId = null;
    public string $title = '';
    public string $description = '';
    public ?int $category_id = null;
    public float $price = 0;
    public bool $is_active = true;

    public bool $showForm = false;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $course = Course::findOrFail($id);
        $this->editingId = $course->id;
        $this->title = $course->title;
        $this->description = $course->description;
        $this->category_id = $course->category_id;
        $this->price = $course->price;
        $this->is_active = $course->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $validated = $this->validate();
        $validated['slug'] = Str::slug($this->title) . '-' . Str::random(5);

        if ($this->editingId) {
            Course::findOrFail($this->editingId)->update($validated);
            session()->flash('message', 'Course updated successfully.');
        } else {
            Course::create($validated);
            session()->flash('message', 'Course created successfully.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Course::findOrFail($id)->delete();
        session()->flash('message', 'Course removed.');
    }

    public function toggleActive(int $id): void
    {
        $course = Course::findOrFail($id);
        $course->update(['is_active' => ! $course->is_active]);
    }

    protected function resetForm(): void
    {
        $this->reset(['editingId', 'title', 'description', 'category_id', 'price', 'is_active']);
        $this->is_active = true;
    }

    public function render()
    {
        $courses = Course::with('category')
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterCategory, fn ($q) => $q->where('category_id', $this->filterCategory))
            ->latest()
            ->paginate(10);

        return view('livewire.course-manager', [
            'courses' => $courses,
            'categories' => Category::active()->get(),
        ]);
    }
}
