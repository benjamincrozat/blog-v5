{{--
Presents a Markdown-managed tool card.
--}}

@props([
    'tool',
])

@php
    $reviewPost = $tool->reviewPost;
    $canReadReview = $reviewPost?->isPublished();

    $badges = collect([
        $tool->pricing_label,
        $tool->has_free_plan ? 'Free plan' : null,
        $tool->has_free_trial ? 'Free trial' : null,
        $tool->is_open_source ? 'Open source' : null,
    ])->filter()->values();
@endphp

<article class="overflow-hidden rounded-2xl bg-gray-100/75 ring-1 ring-black/5">
    <div class="flex flex-col gap-6 p-5 md:p-6">
        @if ($tool->image_url)
            <div class="overflow-hidden rounded-xl bg-white/70">
                <img
                    loading="lazy"
                    src="{{ $tool->image_url }}"
                    alt="{{ $tool->name }}"
                    class="aspect-[16/9] w-full object-cover object-top"
                />
            </div>
        @endif

        <div class="flex flex-wrap gap-2">
            @foreach ($badges as $badge)
                <span class="rounded-full bg-white px-3 py-1 text-xs font-medium tracking-wide text-gray-700 uppercase ring-1 ring-black/5">
                    {{ $badge }}
                </span>
            @endforeach
        </div>

        <div>
            <p class="text-2xl font-medium tracking-tight text-black">
                {{ $tool->name }}
            </p>

            <p class="mt-2 leading-tight text-gray-700">
                {{ $tool->description }}
            </p>
        </div>

        @if (filled($tool->content))
            <x-prose class="grow leading-normal sm:text-balance">
                {!! \App\Markdown\MarkdownRenderer::parse($tool->content) !!}
            </x-prose>
        @endif

        @if (filled($tool->categories))
            <div class="flex flex-wrap gap-2 text-sm text-gray-600">
                @foreach ($tool->categories as $category)
                    <span>#{{ $category }}</span>
                @endforeach
            </div>
        @endif

        <div class="flex flex-wrap gap-3">
            <x-btn
                href="{{ route('merchants.show', $tool->slug) }}"
                primary
                target="_blank"
                rel="sponsored noopener"
            >
                Visit tool
            </x-btn>

            @if ($canReadReview)
                <x-btn
                    href="{{ route('posts.show', $reviewPost) }}"
                    wire:navigate
                >
                    Read review
                </x-btn>
            @endif
        </div>
    </div>
</article>
