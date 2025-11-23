@props([
    'ads',
])

@if (! empty($ads))
    <div
        {{
            $attributes
                ->class('group bg-white/75 fixed bottom-2 sm:bottom-4 group-hover inset-x-2 sm:right-auto sm:left-1/2 sm:-translate-x-1/2 group backdrop-blur-md rounded-b-sm rounded-t-md shadow-xl sm:w-[480px] backdrop-saturate-200 overflow-hidden ring-1 ring-black/10')
        }}
        x-cloak
        x-data="data()"
        x-show="show"
        x-transition:enter="transition ease-out duration-600"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        @mouseenter="pause()"
        @mouseleave="resume()"
        @showcase.window="requestShow()"
    >
        <div class="py-1 px-2.5 flex gap-2 items-center border-b border-black/10">
            <div class="font-normal cursor-default text-sm text-black/75">My sponsors</div>

            <div class="grow"></div>

            <a
                wire:navigate
                href="{{ route('advertise') }}"
                class="p-1 -mr-1 bg-black/4 transition-colors hover:bg-black/7.5 rounded-md"
            >
                <x-heroicon-o-question-mark-circle class="size-4" />
                <span class="sr-only">Become a sponsor</span>
            </a>

            <button
                class="p-1 -mr-1.5 bg-black/4 transition-colors hover:bg-black/7.5 rounded-md"
                @click="closeBanner()"
            >
                <x-heroicon-s-x-mark class="size-4" />
                <span class="sr-only">Close</span>
            </button>
        </div>

        <template x-if="ads.length">
            <div
                class="flex items-center gap-4 sm:gap-6 overflow-x-hidden snap-x snap-mandatory scroll-smooth"
                x-ref="container"
            >
                <template x-for="(ad, index) in ads" x-bind:key="index">
                    <a
                        x-bind:href="ad.url"
                        target="_blank"
                        class="flex items-start gap-4 sm:gap-6 basis-full shrink-0 snap-center sm:py-4 p-4 sm:px-6"
                        x-bind:data-ad-index="index"
                    >
                        <div x-html="ad.icon" class="mt-1"></div>

                        <div class="leading-tight">
                            <h1 class="font-semibold text-black/95" x-text="ad.title"></h1>
                            
                            <p class="text-black/75">
                                <span x-text="ad.description"></span>
                                <span class="font-medium underline" x-text="`${ad.cta} →`"></span>
                            </p>
                        </div>
                    </a>
                </template>
            </div>
        </template>
        
        <template x-if="ads.length">
            <div class="block h-1.25 bg-linear-to-r from-transparent to-blue-600/30" x-bind:style="progressStyle()"></div>
        </template>
    </div>

    <script>
        const ADS_BANNER_DISMISS_DURATION = 24 * 60 * 60 * 1000

        document.addEventListener('alpine:init', () => {
            Alpine.data('data', function () {
                return {
                    ads: {{ Js::from($ads) }},
                    show: false,
                    dismissedUntil: this.$persist(null),
                    currentIndex: 0,
                    cycleDuration: 8000,
                    cycleTimeoutId: null,
                    cycleStartTime: null,
                    progress: 0,
                    progressRequestAnimationFrameId: null,
                    remainingCycleDuration: null,
                    isPaused: false,

                    init() {
                        if (! this.hasAds()) {
                            return
                        }

                        if (this.isDismissed()) {
                            this.show = false
                        }

                        this.$watch('currentIndex', () => this.scrollToCurrent())
                        this.scrollToCurrent()

                        this.startCycle()
                    },

                    startCycle(duration = this.cycleDuration, elapsedOffset = 0) {
                        if (! this.hasAds()) {
                            return
                        }

                        this.clearCycleTimeout()
                        this.stopProgressAnimation()

                        this.cycleStartTime = performance.now() - elapsedOffset
                        this.remainingCycleDuration = duration

                        this.cycleTimeoutId = setTimeout(() => {
                            this.showNext()
                        }, duration)

                        this.animateProgress()
                    },

                    clearCycleTimeout() {
                        if (! this.cycleTimeoutId) {
                            return
                        }

                        clearTimeout(this.cycleTimeoutId)

                        this.cycleTimeoutId = null
                    },

                    showNext() {
                        if (! this.hasAds()) {
                            return
                        }

                        const nextIndex = (this.currentIndex + 1) % this.ads.length

                        this.currentIndex = nextIndex

                        if (! this.isPaused) {
                            this.startCycle(this.cycleDuration)
                        }
                    },

                    pause() {
                        if (this.isPaused || ! this.cycleStartTime) {
                            return
                        }

                        const elapsed = this.elapsedSinceCycleStart()

                        this.remainingCycleDuration = Math.max(this.cycleDuration - elapsed, 0)
                        this.isPaused = true

                        this.clearCycleTimeout()
                        this.stopProgressAnimation()
                    },

                    resume() {
                        if (! this.isPaused) {
                            return
                        }

                        const remaining = this.remainingCycleDuration ?? this.cycleDuration
                        const elapsedOffset = this.cycleDuration - remaining

                        this.isPaused = false

                        this.startCycle(remaining || this.cycleDuration, elapsedOffset)
                    },

                    animateProgress() {
                        this.stopProgressAnimation()

                        const update = () => {
                            const elapsed = this.elapsedSinceCycleStart()
                            const percentage = Math.min(elapsed / this.cycleDuration, 1) * 100

                            this.progress = percentage

                            if (elapsed < this.cycleDuration) {
                                this.progressRequestAnimationFrameId = requestAnimationFrame(update)

                                return
                            }

                            this.progressRequestAnimationFrameId = null
                        }

                        update()
                    },

                    stopProgressAnimation() {
                        if (! this.progressRequestAnimationFrameId) {
                            return
                        }

                        cancelAnimationFrame(this.progressRequestAnimationFrameId)

                        this.progressRequestAnimationFrameId = null
                    },

                    progressStyle() {
                        return `width: ${this.progress}%;`
                    },

                    scrollToCurrent() {
                        this.$nextTick(() => {
                            const container = this.$refs.container

                            if (! container) {
                                return
                            }

                            const target = container.querySelector(`[data-ad-index="${this.currentIndex}"]`)

                            if (! target) {
                                return
                            }

                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest',
                                inline: 'center',
                            })
                        })
                    },

                    closeBanner() {
                        this.show = false
                        this.dismissedUntil = Date.now() + ADS_BANNER_DISMISS_DURATION
                    },

                    requestShow() {
                        if (this.isDismissed()) {
                            return
                        }

                        this.show = true
                    },

                    isDismissed() {
                        if (! this.dismissedUntil) {
                            return false
                        }

                        return Date.now() < this.dismissedUntil
                    },

                    hasAds() {
                        return Array.isArray(this.ads) && this.ads.length > 0
                    },

                    elapsedSinceCycleStart() {
                        if (! this.cycleStartTime) {
                            return 0
                        }

                        return performance.now() - this.cycleStartTime
                    },
                }
            })
        })
    </script>
@endif