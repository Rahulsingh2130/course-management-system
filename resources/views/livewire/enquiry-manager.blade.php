<div>
    <h1 class="text-2xl font-semibold mb-4">Enquiries</h1>

    <div class="flex gap-3 mb-4">
        <select wire:model.live="filterType" class="border border-slate-300 rounded-md px-3 py-2 text-sm">
            <option value="">All types</option>
            @foreach (['general', 'course', 'corporate', 'contact', 'newsletter'] as $t)
                <option value="{{ $t }}">{{ ucfirst($t) }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterStatus" class="border border-slate-300 rounded-md px-3 py-2 text-sm">
            <option value="">All statuses</option>
            <option value="new">New</option>
            <option value="contacted">Contacted</option>
            <option value="closed">Closed</option>
        </select>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-slate-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr>
                    <th class="px-4 py-3">Contact</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Course / Details</th>
                    <th class="px-4 py-3">Received</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($enquiries as $enquiry)
                    <tr class="border-t border-slate-100 align-top" wire:key="enq-{{ $enquiry->id }}">
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $enquiry->name ?: '—' }}</div>
                            <div class="text-slate-500">{{ $enquiry->email }}</div>
                            <div class="text-slate-500">{{ $enquiry->phone }}</div>
                        </td>
                        <td class="px-4 py-3">{{ ucfirst($enquiry->type) }}</td>
                        <td class="px-4 py-3 max-w-xs">
                            @if ($enquiry->course)<div class="font-medium">{{ $enquiry->course->title }}</div>@endif
                            @if ($enquiry->company)<div>{{ $enquiry->company }}@if ($enquiry->team_size) ({{ $enquiry->team_size }} people)@endif</div>@endif
                            @if ($enquiry->funding_source)<div class="text-slate-500">Funding: {{ $enquiry->funding_source }}</div>@endif
                            <div class="text-slate-600">{{ $enquiry->message }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $enquiry->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3">
                            <select wire:change="setStatus({{ $enquiry->id }}, $event.target.value)" class="border border-slate-300 rounded px-2 py-1 text-xs">
                                @foreach (['new', 'contacted', 'closed'] as $s)
                                    <option value="{{ $s }}" @selected($enquiry->status === $s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-3">
                            <button wire:click="delete({{ $enquiry->id }})" wire:confirm="Delete this enquiry?" class="text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No enquiries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $enquiries->links() }}</div>
</div>
