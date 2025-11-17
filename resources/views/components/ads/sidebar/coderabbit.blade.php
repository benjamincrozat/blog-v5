<a
    {{
        $attributes
            ->class('grid gap-4 p-4 !pt-6 leading-tight bg-rose-50/75 rounded-xl text-rose-900')
            ->merge([
                'href' => route('redirect-to-advertiser', [
                    'slug' => 'coderabbit',
                    'utm_medium' => 'sidebar',
                ]),
                'target' => '_blank',
                'x-intersect.once' => $user?->isAdmin() ? null : "pirsch(`Ad shown`, {
                    meta: { name: `coderabbit` }
                })",
            ])
    }}
>
    <img
        loading="lazy"
        src="https://www.gravatar.com/avatar/d58b99650fe5d74abeb9d9dad5da55ad?s=256"
        alt="Benjamin Crozat"
        width="40"
        height="40"
        class="mx-auto h-10 rounded-full"
    />

    <p class="-mt-2 leading-tight text-center"><strong class="font-semibold text-rose-900">“Here's an AI-powered workflow your team can actually use”</strong></p>

    <p>CodeRabbit helps your team review code faster and more effectively.</p>

    <ul class="grid gap-1 -mt-2 ml-3 list-disc list-inside">
        <li>Unlimited repos and reviews</li>
        <li>Actionable PR summaries</li>
        <li>Works in your IDE</li>
        <li>Jira & Linear integration</li>
        <li>SAST and linters built-in</li>
    </ul>

    <img
        loading="lazy"
        src="{{ Vite::asset('resources/img/screenshots/coderabbit.webp') }}"
        width="796"
        height="736"
        alt="CodeRabbit's interface"
        class="mt-5 rounded ring-1 shadow-lg transition-transform scale-125 rotate-1 hover:rotate-0 hover:scale-150 shadow-rose-900/10 ring-rose-900/10"
    />

    <x-btn primary class="w-full bg-orange-500! hover:bg-orange-400! mt-6 text-center !rounded-md cursor-pointer">
        Start free for 14 days
    </x-btn>
</a>
