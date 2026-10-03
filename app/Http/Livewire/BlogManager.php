<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class BlogManager extends Component
{
    use WithPagination;

    public ?int $editingId = null;
    public string $title = '';
    public string $topic = 'General';
    public string $excerpt = '';
    public string $body = '';
    public bool $is_published = true;
    public bool $showForm = false;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'topic' => 'required|string|max:80',
            'excerpt' => 'required|string|max:400',
            'body' => 'required|string',
            'is_published' => 'boolean',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $post = Post::findOrFail($id);
        $this->editingId = $post->id;
        $this->title = $post->title;
        $this->topic = $post->topic;
        $this->excerpt = $post->excerpt;
        $this->body = $post->body;
        $this->is_published = $post->is_published;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            Post::findOrFail($this->editingId)->update($data);
        } else {
            Post::create($data + [
                'slug' => Str::slug($this->title) . '-' . Str::lower(Str::random(4)),
                'read_minutes' => max(1, (int) ceil(str_word_count(strip_tags($this->body)) / 200)),
                'published_at' => now(),
            ]);
        }

        session()->flash('message', 'Post saved.');
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Post::findOrFail($id)->delete();
        session()->flash('message', 'Post deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'title', 'excerpt', 'body', 'showForm']);
        $this->topic = 'General';
        $this->is_published = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.blog-manager', ['posts' => Post::latest('published_at')->paginate(10)]);
    }
}
