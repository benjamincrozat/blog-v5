<a
    {{
        $attributes
            ->class('block ring-1 ring-orange-50/75 text-orange-900 bg-gradient-to-r from-orange-50/75 to-orange-50/25')
            ->merge([
                'href' => route('redirect-to-advertiser', [
                    'slug' => 'coderabbit',
                    'utm_medium' => 'top',
                ]),
                'target' => '_blank',
                'x-intersect.once' => $user?->isAdmin() ? null : "pirsch(`Ad shown`, {
                    meta: { name: `coderabbit` }
                })",
            ])
    }}
>
    <p class="flex gap-4 justify-center items-center p-4 leading-[1.35] text-sm sm:text-base">
        <img
            loading="lazy"
            src="https://www.gravatar.com/avatar/d58b99650fe5d74abeb9d9dad5da55ad?s=256"
            alt="Benjamin Crozat"
            width="32"
            height="32"
            class="w-auto h-9 rounded-full sm:h-8"
        />

        <span>
            "What if you cut code review time & bugs in half, instantly?"
            <span class="font-medium underline">Start&nbsp;free&nbsp;for&nbsp;14&nbsp;days→</span>
        </span>
    </p>
</a>
