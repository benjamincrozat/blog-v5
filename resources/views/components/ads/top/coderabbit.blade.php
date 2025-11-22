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
    <p class="flex gap-4 justify-center items-center p-4 leading-[1.35]">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 313.000305 313" class="size-8 flex-none"><g fill="none"><path fill="#D75D2C" d="M156.5003 313c86.432 0 156.5-70.067 156.5-156.5 0-86.4326-70.068-156.5-156.5-156.5C70.0674 0-.0000047 70.0674-.0000047 156.5-.0000047 242.933 70.0674 313 156.5003 313Z"/><path fill="#FEFEFE" d="M262.7733 130.577s-21.805-27.874-49.215-29.468c-17.688-1.044-21.973 1.319-22.741 3.079-1.098-9.1258-8.898-51.4055-62.784-60.3657 6.879 49.449 35.288 36.5643 52.019 70.6467 0 0-28.233-38.3749-74.649-24.2458 0 0 16.918 35.5168 66.958 42.7738 0 0 4.01 13.744 5.219 16.164 0 0-77.066-40.19-100.4661 36.945-17.4148-3.945-23.2561 14.96-3.2401 27.874 0 0 3.4058-13.525 11.699-17.538 0 0-17.797 19.848 3.1309 43.619h75.1143c1.815-3.007 9.848-18.825-10.019-30.798 14.024-.201 25.439 26.252 37.72 30.98h17.863c.604-1.469 1.868-5.867-1.1-9.824-4.574-5.247-14.589-4.537-14.5-14.24 3.459-45.139 71.143-31.277 68.991-85.602Z"/></g></svg>

        <span>
            "What if you cut code review time & bugs in half, instantly?"
            <span class="font-medium underline">Start&nbsp;free&nbsp;for&nbsp;14&nbsp;days→</span>
        </span>
    </p>
</a>
