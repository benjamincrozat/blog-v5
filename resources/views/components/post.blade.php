@props(['post'])

<div {{ $attributes }}>
    @if ($post['image'])
        <a wire:navigate href="{{ route('posts.show', $post['slug']) }}">
            <img src="{{ $post['image'] }}" alt="{{ $post['title']  }}" class="object-cover transition-opacity shadow-md shadow-black/5 rounded-xl aspect-video hover:opacity-50 ring-1 ring-black/5" />
        </a>
    @endif

    <div class="mt-4">
        @if ($post['modified_at'])
            Updated on
            <time datetime="{{ $post['modified_at'] }}">
                {{ $post['modified_at']->isoFormat('LL') }}
            </time>
        @else
            <time datetime="{{ $post['published_at'] }}">
                {{ $post['published_at']->isoFormat('LL') }}
            </time>
        @endif
    </div>

    <div class="flex items-center justify-between gap-6 mt-2">
        <a wire:navigate href="{{ route('posts.show', $post['slug']) }}" class="font-bold transition-colors text-xl/tight hover:text-blue-600">
            {{ $post['title'] }}
        </a>

        <img
            src="https://www.gravatar.com/avatar/d58b99650fe5d74abeb9d9dad5da55ad?s=256"
            alt="Benjamin Crozat"
            class="rounded-full ring-1 ring-black/5 size-10"
        />
    </div>

    <div class="mt-2">
        {!! Str::markdown($post['description']) !!}
    </div>
</div>
