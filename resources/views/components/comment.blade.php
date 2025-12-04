@php
// Temporary fix.
$parentId = $this->parentId ?? $attributes->get('parentId');
$commentUser = $comment->user;
@endphp

<div {{ $attributes }}>
    <div class="flex gap-4">
        <img
            loading="lazy"
            src="{{ $commentUser->avatar }}"
            alt="{{ $commentUser->name ?? 'Deleted user' }}"
            class="flex-none mt-1 rounded-full ring-1 shadow-sm shadow-black/5 ring-black/10 size-7 md:size-8"
        />

        <div class="grow">
            <div class="flex gap-4 justify-between">
                <div>
                    <a href="{{ data_get($commentUser->github_data, 'user.html_url') ?? '#' }}" target="_blank" class="font-medium">
                        {{ $commentUser->name ?? 'Deleted user' }}
                    </a>

                    <span class="ml-1 text-gray-500">
                        {{ $comment->created_at->diffForHumans(short: true) }}
                    </span>
                </div>

                @empty($hideActionButtons)
                    <div class="flex gap-2 items-center">
                        @if ($user?->isAdmin())
                            <a
                                href="{{ route('filament.admin.resources.comments.edit', $comment) }}"
                                class="grid place-items-center bg-gray-100 rounded-full transition-colors hover:bg-gray-200 size-8"
                            >
                                <x-heroicon-o-pencil class="size-4" />
                                <span class="sr-only">Edit</span>
                            </a>
                        @endcan

                        @can('delete', $comment)
                            <button
                                class="grid place-items-center text-red-600 bg-red-50 rounded-full transition-colors hover:bg-red-100 size-8"
                                wire:click="delete({{ $comment->id }})"
                                wire:confirm="Are you sure you want to delete this comment?"
                                wire:loading.attr="disabled"
                            >
                                <x-heroicon-o-trash class="size-4" />
                                <span class="sr-only">Delete</span>
                            </button>
                        @endcan
                    </div>
                @endempty
            </div>

            <div class="px-4 py-3 mt-2 bg-gray-100 rounded-lg">
                <x-prose>
                    {!! Lightdown::parse($comment->content) !!}
                </x-prose>

                @empty($hideReplyButton)
                    <div class="mt-4 text-right">
                        <button
                            class="inline-flex hover:text-blue-600 transition-colors disabled:opacity-30 gap-[.35rem] items-center font-medium"
                            @if (auth()->guest())
                                disabled
                            @else
                                wire:click="$set('parentId', {{ $comment->id }})"
                            @endif
                            {{ $parentId === $comment->id ? 'disabled' : '' }}
                        >
                            Reply
                            <x-heroicon-o-arrow-uturn-down class="size-4 -scale-x-100" />
                        </button>
                    </div>
                @endempty
            </div>
        </div>
    </div>

    @if ($parentId === $comment->id)
        <div
            class="mt-8 ml-11 md:ml-12"
            x-trap="true"
            @keydown.esc="$wire.$set('parentId', null)"
        >
            <livewire:comment-form
                wire:key="comment-form-{{ $comment->id }}"
                :parentId="$comment->id"
                label="Your reply"
            />
        </div>
    @endif

    @if ($comment->children->isNotEmpty())
        <ul class="grid gap-8 mt-8 ml-11 md:ml-12">
            @foreach ($comment->children as $child)
                <li>
                    <x-comment :comment="$child" />
                </li>
            @endforeach
        </ul>
    @endif
</div>
