<x-app>
    <x-section title="Your comments ({{ $comments->total() }})">
        <div class="grid gap-4 mt-8 md:grid-cols-2">
            @foreach ($comments as $comment)
                <a href="{{ route('posts.show', $comment->post) }}#comments" class="flex gap-4 p-4 rounded-xl transition-colors bg-gray-100/75 group md:gap-6 hover:bg-blue-50 md:p-6">
                    <div class="flex gap-4">
                        <img
                            loading="lazy"
                            src="{{ $comment->user->avatar }}"
                            alt="{{ $comment->user->name }}"
                            class="flex-none mt-1 rounded-full ring-1 shadow-sm shadow-black/5 ring-black/10 size-7 md:size-8"
                        />

                        <div>
                            <div>
                                <span class="font-medium text-black">{{ $comment->user->name }}</span>

                                <span class="ml-1 text-gray-500">
                                    {{ $comment->created_at->diffForHumans(short: true) }}
                                </span>
                            </div>

                            <div class="mt-2">
                                {!! $comment->stripped !!}
                            </div>
                        </div>
                    </div>

                    <x-heroicon-o-chevron-right class="flex-none self-center text-gray-400 transition-colors size-4 group-hover:text-blue-400" />
                </a>
            @endforeach
        </div>

        <x-pagination
            :paginator="$comments"
            class="mt-16"
        />
    </x-section>
</x-app>
