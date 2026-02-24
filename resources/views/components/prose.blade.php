{{--
Displays the components prose component and accepts component props, Blade attributes, and slot content.
--}}

<div {{ $attributes->class('max-w-none prose prose-a:decoration-1 prose-a:decoration-black/30 prose-a:font-normal prose-a:underline-offset-4 prose-headings:font-medium prose-headings:scroll-mt-4 prose-img:shadow-xl prose-img:ring-1 prose-img:ring-black/5 prose-img:rounded-sm prose-pre:ring-1 prose-pre:ring-black/5 prose-pre:shadow-md prose-pre:shadow-black/5 prose-pre:overflow-x-auto prose-pre:whitespace-pre-wrap prose-pre:wrap-anywhere prose-strong:font-medium') }}>
    {{ $slot }}
</div>
