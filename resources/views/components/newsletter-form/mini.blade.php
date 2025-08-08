<div {{ $attributes->class('hidden rounded-xl leading-tight lg:block p-4 bg-[#F53103] text-red-50') }}>
    <p class="font-bold tracking-widest text-white uppercase text-balance">
        Level up your career with Laravel
    </p>

    <p class="mt-3 text-balance">
        Become your team's most valuable member by leveraging the framework's strengths.
    </p>

    <x-form class="mt-4">
        <x-form.input
            id="email"
            name="email"
            label="Your email"
            placeholder="you@example.com"
            required
            class="bg-transparent! border-0 border-b border-red-300 focus:border-white rounded-none focus:ring-0 placeholder-red-300 pt-0! px-0!"
        />

        <x-btn primary class="mt-4 rounded-md! bg-white! text-[#F53103]! hover:bg-white/50!">
            Grab my free guide
        </x-btn>
    </x-form>
</div>
