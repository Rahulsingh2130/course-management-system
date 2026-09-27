<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Courses</h1>
        <button wire:click="create" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
            + New Course
        </button>
    </div>

    <div class="flex gap-3 mb-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search courses..."
               class="border border-slate-300 rounded-md px-3 py-2 text-sm w-64">

        <select wire:model.live="filterCategory" class="border border-slate-300 rounded-md px-3 py-2 text-sm">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    @if ($showForm)
        <div class="bg-white border border-slate-200 rounded-lg p-5 mb-6">
            <h2 class="font-semibold mb-3">{{ $editingId ? 'Edit Course' : 'New Course' }}</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-slate-600">Title</label>
                    <input wire:model="title" type="text" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
                    @error('title') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-sm text-slate-600">Category</label>
                    <select wire:model="category_id" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="text-sm text-slate-600">Description</label>
                    <textarea wire:model="description" rows="3" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm"></textarea>
                </div>

                <div>
                    <label class="text-sm text-slate-600">Price (₹)</label>
                    <input wire:model="price" type="number" step="0.01" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
                    @error('price') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center gap-2 mt-6">
                    <input wire:model="is_active" type="checkbox" id="is_active">
                    <label for="is_active" class="text-sm text-slate-600">Active (visible on public catalog)</label>
                </div>
            </div>

            <div class="mt-4 flex gap-2">
                <button wire:click="save" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
                    Save
                </button>
                <button wire:click="$set('showForm', false)" class="px-4 py-2 rounded-md text-sm border border-slate-300">
                    Cancel
                </button>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $course)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">{{ $course->title }}</td>
                        <td class="px-4 py-3">{{ $course->category->name }}</td>
                        <td class="px-4 py-3">₹{{ number_format($course->price, 2) }}</td>
                        <td class="px-4 py-3">
                            <button wire:click="toggleActive({{ $course->id }})"
                                class="px-2 py-1 rounded text-xs {{ $course->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $course->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-4 py-3 space-x-2">
                            <button wire:click="edit({{ $course->id }})" class="text-indigo-600 hover:underline">Edit</button>
                            <button wire:click="delete({{ $course->id }})" wire:confirm="Delete this course?" class="text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $courses->links() }}
    </div>
</div>
