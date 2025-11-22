<a
    {{
        $attributes
            ->class('block ring-1 ring-orange-50/75 text-orange-900 bg-gradient-to-r from-orange-50/75 to-orange-50/25')
            ->merge([
                'href' => route('redirect-to-advertiser', [
                    'slug' => 'sevalla',
                    'utm_medium' => 'top',
                ]),
                'target' => '_blank',
                'x-intersect.once' => $user?->isAdmin() ? null : "pirsch(`Ad shown`, {
                    meta: { name: `Sevalla` }
                })",
            ])
    }}
>
    <p class="flex gap-4 justify-center items-center p-4 leading-[1.35]">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 560 560" class="size-8 flex-none"><g fill="none"><path fill="#FFF" d="M155 70h279v418H155z"/><path fill="#FA7216" d="M0 110C0 49.249 49.249 0 110 0h339.266c60.751 0 110 49.249 110 110v339.266c0 60.751-49.249 110-110 110H110c-60.751 0-110-49.249-110-110V110Z"/><g fill="#FFF"><path d="M157.294 182.778h59.469v38.117c0 8.496 0 27.095 15.353 27.095h110.29l50.14 50.86c.441.601 1.107 1.167 1.884 1.827 2.931 2.493 7.445 6.332 7.445 18.494v57.863h-59.469v-38.116c0-8.496 0-27.095-15.353-27.095H216.18l-49.558-50.86c-.441-.601-1.106-1.167-1.883-1.828-2.931-2.492-7.445-6.331-7.445-18.493v-57.864Z"/><path d="M216.763 116.878v65.9h125.934v-65.9H216.763Zm-.583 260.156v65.9h126.517v-65.9H216.18Z"/><path d="m342.697 182.778-.291 65.212h59.566l-.097-65.212h-59.178ZM157.294 311.823v65.211h58.886v-65.211h-58.886Z"/></g></g></svg>

        <span>
            “Deploy web apps using the power of Docker with Sevalla.”
            <span class="font-medium underline">Claim&nbsp;$50&nbsp;→</span>
        </span>
    </p>
</a>
