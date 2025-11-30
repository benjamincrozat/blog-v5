<div {{ $attributes->class('bg-gray-100') }}>
    <footer class="container py-8 lg:max-w-(--breakpoint-md) *:[&_a]:underline *:[&_a]:font-medium">
        <nav class="grid grid-cols-2 gap-y-2 gap-x-6 sm:grid-cols-6 md:grid-cols-7 sm:place-items-center">
            <a wire:navigate href="{{ route('home') }}">Home</a>
            <a wire:navigate href="{{ route('posts.index') }}">Articles</a>
            <a wire:navigate href="{{ route('categories.index') }}">Categories</a>
            <a wire:navigate href="{{ route('links.index') }}">Links</a>
            <a wire:navigate href="{{ route('advertise') }}">Advertise</a>
            <a href="{{ route('home') }}#about">About</a>
            <a href="mailto:hello@benjamincrozat.com">Contact</a>
        </nav>

        <p class="mt-8 text-center text-balance">
            This platform is hosted on <a href="{{ route('merchants.show', 'digitalocean') }}" target="_blank">DigitalOcean</a>. Analytics provided by <a href="{{ route('merchants.show', 'pirsch-analytics') }}" target="_blank">Pirsch</a>.
        </p>

        <p class="mt-8 text-center text-gray-400">Please don't steal my content. © {{ date('Y') }} blah blah blah.</p>
    </footer>
</div>
