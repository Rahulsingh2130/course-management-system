<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Workshop Schedule</h1>
        <button wire:click="create" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
            + Schedule Batch
        </button>
    </div>

    @if ($showForm)
        <div class="bg-white border border-slate-200 rounded-lg p-5 mb-6">
            <h2 class="font-semibold mb-3">{{ $editingId ? 'Edit Batch' : 'New Batch' }}</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-slate-600">Course</label>
                    <select wire:model="course_id" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
                        <option value="">Select course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                    @error('course_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-sm text-slate-600">Instructor</label>
                    <select wire:model="instructor_id" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
                        <option value="">Unassigned</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm text-slate-600">Batch Name</label>
                    <input wire:model="batch_name" type="text" placeholder="e.g. Weekend Batch - Jan 2026"
                           class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
                    @error('batch_name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-sm text-slate-600">Seat Limit</label>
                    <input wire:model="seat_limit" type="number" min="1" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="text-sm text-slate-600">Starts At</label>
                    <input wire:model="starts_at" type="datetime-local" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
                    @error('starts_at') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-sm text-slate-600">Ends At</label>
                    <input wire:model="ends_at" type="datetime-local" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
                    @error('ends_at') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-4 flex gap-2">
                <button wire:click="save" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">Save</button>
                <button wire:click="$set('showForm', false)" class="px-4 py-2 rounded-md text-sm border border-slate-300">Cancel</button>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr>
                    <th class="px-4 py-3">Batch</th>
                    <th class="px-4 py-3">Course</th>
                    <th class="px-4 py-3">Instructor</th>
                    <th class="px-4 py-3">Schedule</th>
                    <th class="px-4 py-3">Seats</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($workshops as $workshop)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">{{ $workshop->batch_name }}</td>
                        <td class="px-4 py-3">{{ $workshop->course->title }}</td>
                        <td class="px-4 py-3">{{ $workshop->instructor->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $workshop->starts_at->format('d M Y, h:i A') }}</td>
                        <td class="px-4 py-3">{{ $workshop->seatsRemaining() }} / {{ $workshop->seat_limit }}</td>
                        <td class="px-4 py-3 space-x-2">
                            <button wire:click="edit({{ $workshop->id }})" class="text-indigo-600 hover:underline">Edit</button>
                            <button wire:click="delete({{ $workshop->id }})" wire:confirm="Remove this batch?" class="text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $workshops->links() }}
    </div>
</div>
