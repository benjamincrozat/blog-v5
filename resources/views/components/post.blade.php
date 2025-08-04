@props(['post'])

<div {{ $attributes->class('flex flex-col h-full') }}>
    <a wire:navigate href="{{ route('posts.show', $post->slug) }}">
        @if ($post->hasImage())
            <div class="relative">
                <img
                    fetchpriority="high"
                    src="{{ $post->image_url }}"
                    alt="{{ $post->title  }}"
                    class="object-cover rounded-xl ring-1 shadow-md transition-opacity shadow-black/5 aspect-video hover:opacity-50 ring-black/5"
                />
            </div>
        @else
            @php
            $bgColors = collect([
                'bg-amber-600', 'bg-blue-600', 'bg-cyan-600', 'bg-emerald-600', 'bg-gray-600', 'bg-green-600', 'bg-indigo-600', 'bg-lime-600', 'bg-pink-600', 'bg-purple-600', 'bg-red-600', 'bg-sky-600', 'bg-teal-600', 'bg-yellow-600',
            ])->random();
            @endphp

            <div class="{{ $bgColors }} shadow-md ring-1 ring-black/5 aspect-video rounded-xl shadow-black/5"></div>
        @endif
    </a>

    <x-categories :categories="$post->categories" class="mt-6">
        @if ($post->quiz && ! $post->is_commercial)
            <a
                wire:navigate
                href="#"
                class="px-2 py-1 text-xs font-medium text-orange-600 uppercase rounded-sm border border-orange-300"
                data-pirsch-event="Clicked quiz badge"
                data-pirsch-meta-post="{{ $post->title }}"
            >
                Quiz
            </a>
        @endif
    </x-categories>

    <div class="flex gap-6 justify-between items-center mt-5">
        <a
            wire:navigate
            href="{{ route('posts.show', $post->slug) }}"
            class="font-bold transition-colors text-xl/tight hover:text-blue-600"
            data-pirsch-event="Clicked post title"
            data-pirsch-meta-title="{{ $post->title }}"
        >
            {{ $post->title }}
        </a>

        <a
            wire:navigate
            href="{{ route('authors.show', $post->user->slug) }}"
            class="flex-none"
            data-pirsch-event="Clicked post author"
            data-pirsch-meta-name="{{ $post->user->name }}"
        >
            <img
                src="{{ $post->user->avatar }}"
                alt="{{ $post->user->name }}"
                class="rounded-full ring-1 ring-black/5 size-10"
            />
        </a>
    </div>

    <div class="mt-4 grow">
        {!! Str::markdown($post->description ?? '') !!}
    </div>

    <div class="grid grid-cols-none grid-flow-col auto-cols-fr gap-4 mt-6 text-sm/tight">
        <div class="flex-1 p-3 text-center bg-gray-50 rounded-lg">
            <x-heroicon-o-calendar class="mx-auto mb-1 opacity-75 size-5" />
            {{ ($post->modified_at ?? $post->published_at)->isoFormat('ll') }}
        </div>

        @if (! $post->is_commercial)
            <a
                href="{{ route('posts.show', $post->slug) }}#comments"
                class="group"
                data-pirsch-event="Clicked comments"
                data-pirsch-meta-post="{{ $post->title }}"
            >
                <div class="flex-1 p-3 text-center bg-gray-50 rounded-lg transition-colors hover:bg-blue-50 group-hover:text-blue-900">
                    <x-heroicon-o-chat-bubble-oval-left-ellipsis class="mx-auto mb-1 opacity-75 size-5" />
                    {{ $post->comments_count }} {{ trans_choice('comment|comments', $post->comments_count) }}
                </div>
            </a>
        @endif

        <div class="flex-1 p-3 text-center bg-gray-50 rounded-lg">
            <x-heroicon-o-clock class="mx-auto mb-1 opacity-75 size-5" />
            {{ trans_choice(':count min|:count mins', $post->read_time) }}
        </div>
    </div>
</div>
