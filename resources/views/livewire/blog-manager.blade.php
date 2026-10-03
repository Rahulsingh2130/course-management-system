<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Blog Posts</h1>
        <button wire:click="create" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">New post</button>
    </div>

    @if ($showForm)
        <form wire:submit="save" class="bg-white rounded-lg border border-slate-100 shadow-sm p-5 mb-6 space-y-3">
            <div>
                <input wire:model="title" placeholder="Title" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
                @error('title')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <input wire:model="topic" placeholder="Topic" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
            <div>
                <textarea wire:model="excerpt" rows="2" placeholder="Short excerpt" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm"></textarea>
                @error('excerpt')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <textarea wire:model="body" rows="8" placeholder="Body (HTML allowed)" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm font-mono"></textarea>
                @error('body')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_published"> Published</label>
            <div class="flex gap-2">
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm">Save</button>
                <button type="button" wire:click="cancel" class="px-4 py-2 rounded-md text-sm border border-slate-300">Cancel</button>
            </div>
        </form>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr><th class="px-4 py-3">Title</th><th class="px-4 py-3">Topic</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Date</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr class="border-t border-slate-100" wire:key="post-{{ $post->id }}">
                        <td class="px-4 py-3">{{ $post->title }}</td>
                        <td class="px-4 py-3">{{ $post->topic }}</td>
                        <td class="px-4 py-3">{{ $post->is_published ? 'Published' : 'Draft' }}</td>
                        <td class="px-4 py-3">{{ $post->published_at?->format('d M Y') }}</td>
                        <td class="px-4 py-3 space-x-3">
                            <button wire:click="edit({{ $post->id }})" class="text-indigo-600 hover:underline">Edit</button>
                            <button wire:click="delete({{ $post->id }})" wire:confirm="Delete this post?" class="text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $posts->links() }}</div>
</div>
