@if (session('status') || ! empty(request()->submitted))
    <div
        {{ 
            $attributes->class([
                'bg-white/75 ring-1 ring-black/10 fixed bottom-4 inset-x-4 sm:left-1/2 sm:right-auto cursor-default shadow-lg z-10 sm:-translate-x-1/2 backdrop-blur-md sm:min-w-[480px] rounded-lg rounded-b',
                'bg-green-50/75 text-green-900 shadow-green-900/50' => session('status_type') === 'success',
                'bg-blue-50/75 text-blue-900 shadow-blue-900/50' => session('status_type') === 'info',
                'bg-red-50/75 text-red-900 shadow-red-900/50' => session('status_type') === 'error',
            ])
        }}
        x-data="{
            show: false,
            progress: 0,
            timeout: null,
            frame: null,
            start() {
                this.show = true
                this.animateProgress()

                this.timeout = setTimeout(() => this.hide(), 5000)
            },
            hide() {
                this.show = false
                this.resetProgress()
                this.clearTimers()
            },
            animateProgress() {
                this.resetProgress()

                this.frame = requestAnimationFrame(() => {
                    this.frame = requestAnimationFrame(() => {
                        this.progress = 100
                    })
                })
            },
            resetProgress() {
                this.progress = 0
            },
            clearTimers() {
                if (this.timeout) {
                    clearTimeout(this.timeout)
                    this.timeout = null
                }

                if (this.frame) {
                    cancelAnimationFrame(this.frame)
                    this.frame = null
                }
            },
        }"
        x-cloak
        x-init="setTimeout(() => start(), 100)"
        x-show="show"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:leave-end="opacity-0 translate-y-4"
        x-transition:leave="transition ease-in duration-300"
    >
        <button
            class="absolute top-2 right-2 opacity-75 p-1 bg-black/4 transition-colors hover:bg-black/7.5 rounded-md"
            @click="() => {
                hide()
    
                if (typeof pirsch === 'function') {
                    pirsch('Dismissed the status message.')
                }
            }"
        >
            <x-heroicon-o-x-mark class="size-4" />
            <span class="sr-only">Close</span>
        </button>

        <p class="mt-4 px-4 py-3">
            @if (! empty(request()->submitted))
                Your link has been submitted for validation.
            @else
                {{ session('status') }}
            @endif
        </p>

        <div @class([
            'h-2 m-1 mt-0 overflow-hidden rounded-lg bg-gray-600/10',
            'bg-green-600/10!' => session('status_type') === 'success',
            'bg-blue-600/10!' => session('status_type') === 'info',
            'bg-red-600/10!' => session('status_type') === 'error',
        ])">
            <div
                @class([
                    'h-full bg-linear-to-r from-transparent to-gray-600 w-0 transition-all ease-linear rounded-full',
                    'to-green-600!' => session('status_type') === 'success',
                    'to-blue-600!' => session('status_type') === 'info',
                    'to-red-600!' => session('status_type') === 'error',
                ])
                :style="show
                    ? { width: progress + '%', transitionDuration: '5000ms' }
                    : { width: '0%', transitionDuration: '0ms' }
                "
            ></div>
        </div>
    </div>
@endsession
