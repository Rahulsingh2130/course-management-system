<div>
    <h1 class="text-2xl font-semibold mb-4">Enrollments</h1>

    <div class="flex gap-3 mb-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by student name..."
               class="border border-slate-300 rounded-md px-3 py-2 text-sm w-64">

        <select wire:model.live="filterWorkshop" class="border border-slate-300 rounded-md px-3 py-2 text-sm">
            <option value="">All batches</option>
            @foreach ($workshops as $workshop)
                <option value="{{ $workshop->id }}">{{ $workshop->batch_name }}</option>
            @endforeach
        </select>

        <select wire:model.live="filterStatus" class="border border-slate-300 rounded-md px-3 py-2 text-sm">
            <option value="">All statuses</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr>
                    <th class="px-4 py-3">Student</th>
                    <th class="px-4 py-3">Batch</th>
                    <th class="px-4 py-3">Course</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Enrolled On</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($enrollments as $enrollment)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">{{ $enrollment->user->name }}</td>
                        <td class="px-4 py-3">{{ $enrollment->workshop->batch_name }}</td>
                        <td class="px-4 py-3">{{ $enrollment->workshop->course->title }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'px-2 py-1 rounded text-xs',
                                'bg-yellow-100 text-yellow-700' => $enrollment->status === 'pending',
                                'bg-green-100 text-green-700' => $enrollment->status === 'confirmed',
                                'bg-red-100 text-red-700' => $enrollment->status === 'cancelled',
                            ])>
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $enrollment->enrolled_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 space-x-2">
                            @if ($enrollment->status !== 'confirmed')
                                <button wire:click="confirm({{ $enrollment->id }})" class="text-green-600 hover:underline">Confirm</button>
                            @endif
                            @if ($enrollment->status !== 'cancelled')
                                <button wire:click="cancel({{ $enrollment->id }})" class="text-red-600 hover:underline">Cancel</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $enrollments->links() }}
    </div>
</div>
