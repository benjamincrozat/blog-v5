<div
    class="fixed inset-4"
    x-cloak
    x-data="{ open: false }"
    x-show="open"
>
    <div
        class="bg-white overflow-y-auto max-h-full flex flex-col rounded-xl ring-1 mx-auto shadow-2xl ring-black/10 w-full max-w-[480px]"
        x-cloak
        x-show="open"
        x-transition.duration.300ms
        x-trap.noscroll="open"
        @click.away="open = false"
        @keydown.esc="open = false"
        @keydown.arrow-down.stop.prevent="$focus.next()"
        @keydown.arrow-up.stop.prevent="$focus.prev()"
        @keydown.meta.k.window="open = true"
        @search.window="open = true"
    >
        <div class="relative px-[.35rem] py-2 text-sm font-medium text-center border-b border-black/10">
            <button
                class="grid absolute inset-y-1/2 -translate-y-1/2 text-gray-500 place-items-center rounded-full bg-black/[.06] size-6"
                @click="open = false"
            >
                <x-heroicon-o-x-mark class="size-4" />
                <span class="sr-only">Close</span>
            </button>

            <p class="cursor-default">Search for posts and links</p>
        </div>

        <div class="flex relative items-center">
            <x-heroicon-o-magnifying-glass
                class="absolute left-3 top-1/2 text-gray-500 -translate-y-1/2 size-4"
            />

            <input
                type="search"
                wire:model.live="query"
                placeholder="Search"
                autofocus
                class="grow ring-0 border-0 pr-3 pl-9 py-[.65rem] bg-transparent border-b border-black/10 placeholder-black/10"
            />
        </div>

        @if (! empty($query))
            <div class="growp">
                <div>
                    <p class="sticky top-0 z-10 px-4 py-3 text-sm font-medium text-black uppercase border-b border-gray-200 backdrop-blur-md bg-white/75">Posts</p>

                    @if ($posts->isNotEmpty())
                        <ul>
                            @foreach ($posts as $post)
                                <li class="group">
                                    <a
                                        wire:navigate
                                        href="{{ route('posts.show', $post) }}"
                                        class="flex gap-4 items-start p-4 leading-tight border-b transition-colors focus:outline-none focus:bg-blue-600/75 focus:text-white border-black/10 group-last:border-b-0"
                                    >
                                        <img
                                            loading="lazy"
                                            src="{{ $post->image_url }}"
                                            alt="{{ $post->title }}"
                                            width="48"
                                            height="48"
                                            class="mt-1 rounded-md ring-1 shadow-md size-10 ring-black/10 shadow-black/10 md:size-12"
                                        />

                                        <div>
                                            <p class="font-medium">
                                                {!! $post->title !!}
                                            </p>

                                            <p class="mt-2 opacity-75">
                                                {!! $post->description !!}
                                            </p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="p-4 text-center text-gray-500">
                            No posts found for "{{ $query }}".
                        </p>
                    @endif
                </div>

                <div>
                    <p class="sticky top-0 z-10 px-4 py-3 text-sm font-medium text-black uppercase border-gray-200 backdrop-blur-md border-y bg-white/75">Links</p>

                    @if ($links->isNotEmpty())
                        <ul>
                            @foreach ($links as $link)
                                <li class="group">
                                    <a
                                        wire:navigate
                                        href="{{ $link->url }}"
                                        class="flex gap-4 items-start p-4 leading-tight border-b transition-colors focus:outline-none focus:bg-blue-600/75 focus:text-white border-black/10 group-last:border-b-0"
                                    >
                                        <img
                                            loading="lazy"
                                            src="{{ $link->image_url }}"
                                            alt="{{ $link->title }}"
                                            width="48"
                                            height="48"
                                            class="mt-1 rounded-md ring-1 shadow-md size-10 ring-black/10 shadow-black/10 md:size-12"
                                        />

                                        <div>
                                            <p class="font-medium">
                                                {!! $link->title !!}
                                            </p>

                                            <p class="mt-2 opacity-75">
                                                {!! $link->description !!}
                                            </p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="p-4 text-center text-gray-500">No links found for "{{ $query }}".</p>
                    @endif
                </div>
            </div>
        @else
            <p class="p-4 text-center text-gray-400">Try to type something…</p>
        @endif
    </div>
</div>
