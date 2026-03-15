{{--
Shows a compact post row and accepts the post, attributes, and slot content forwarded by the caller.
--}}

@props(['post'])

<div {{ $attributes->class('flex items-center gap-4 md:gap-6') }}>
    <a wire:navigate href="{{ route('posts.show', $post->slug) }}" class="w-[48px] flex-none">
        @if ($post->hasImage())
            <img
                fetchpriority="high"
                src="{{ $post->image_url }}"
                alt="{{ $post->title  }}"
                class="object-cover ring-1 shadow-md transition-opacity shadow-black/5 aspect-square hover:opacity-50 ring-black/5 rounded"
            />
        @else
            <div class="{{ \App\Support\PlaceholderCardColor::for($post->slug) }} shadow-md ring-1 ring-black/5 rounded aspect-square shadow-black/5"></div>
        @endif
    </a>

    <div>
        <p class="leading-tight line-clamp-1">
            <a
                wire:navigate
                href="{{ route('posts.show', $post->slug) }}"
                class="font-bold transition-colors hover:text-blue-600"
            >
                {{ $post->title }}
            </a>
        </p>

        <div class="flex flex-wrap items-center gap-x-3 mt-1">
            <a wire:navigate href="{{ route('authors.show', $post->user->slug) }}" class="hover:text-blue-600 transition-colors">
                <p class="flex items-center gap-2">
                    <x-heroicon-o-user class="size-[.65lh] opacity-75" />
                    {{ $post->user->name }}
                </p>
            </a>

            <div class="text-xs opacity-50">/</div>

            <p class="flex items-center gap-2">
                <x-heroicon-o-calendar class="size-[.65lh] opacity-75" />
                {{ ($post->modified_at ?? $post->published_at)->isoFormat('ll') }}
            </p>

            <div class="text-xs opacity-50">/</div>

            <a wire:navigate href="{{ route('posts.show', $post->slug) }}#comments" class="hover:text-blue-600 transition-colors">
                <p class="flex items-center gap-2">
                    <x-heroicon-o-chat-bubble-oval-left class="size-[.65lh] opacity-75" />
                    {{ $post->comments_count }}
                </p>
            </a>
        </div>
    </div>
</div>
