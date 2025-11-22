@props([
    'ads',
])

@if (! empty($ads))
    <div
        {{
            $attributes
                ->class('group fixed bottom-2 sm:bottom-4 group-hover inset-x-2 sm:right-auto sm:left-1/2 sm:-translate-x-1/2 bg-white/75 group backdrop-blur-md rounded-md shadow-xl sm:w-[480px] backdrop-saturate-200 overflow-hidden ring-1 ring-black/10')
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
        <div class="py-2 px-3 flex items-center border-b border-black/10">
            <div class="font-medium text-sm">Announcements</div>

            <div class="grow"></div>

            <button @click="closeBanner()">
                <x-heroicon-s-x-mark class="size-4" />
                <span class="sr-only">Close</span>
            </button>
        </div>

        <template x-if="ads.length">
            <div
                class="flex items-center gap-4 sm:gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth"
                x-ref="adsContainer"
            >
                <template x-for="(ad, index) in ads" x-bind:key="index">
                    <div
                        class="flex items-center gap-4 sm:gap-6 basis-full shrink-0 snap-center sm:py-4 p-4 sm:px-6"
                        x-bind:data-ad-index="index"
                    >
                        <x-heroicon-s-academic-cap class="size-8 flex-none" />

                        <div class="leading-tight">
                            <h1 class="font-semibold text-black" x-text="ad.title"></h1>

                            <p x-text="ad.description"></p>
                        </div>
                    </div>
                </template>
            </div>
        </template>
        
        <div class="h-1.5 overflow-hidden">
            <span class="block h-full bg-blue-600/20" x-bind:style="progressStyle()"></span>
        </div>
    </div>

    <script>
        const ADS_BANNER_DISMISS_DURATION = 24 * 60 * 60 * 1000

        document.addEventListener('alpine:init', () => {
            Alpine.data('data', function () {
                return {
                    ads: {{ Js::from($ads) }},
                    show: false,
                    dismissedUntil: this.$persist(null).as('ads-bottom-banner-dismissed-until'),
                    currentIndex: 0,
                    cycleDuration: 5000,
                    cycleTimeoutId: null,
                    cycleStartTime: null,
                    progress: 0,
                    progressRequestAnimationFrameId: null,
                    remainingCycleDuration: null,
                    isPaused: false,

                    init() {
                        if (! this.ads.length) {
                            return
                        }

                        if (this.isDismissed()) {
                            this.show = false
                        }

                        this.scrollToCurrent()

                        this.startCycle()
                    },

                    startCycle(duration = this.cycleDuration, elapsedOffset = 0) {
                        if (! this.ads.length) {
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
                        if (! this.ads.length) {
                            return
                        }

                        const nextIndex = (this.currentIndex + 1) % this.ads.length

                        this.currentIndex = nextIndex

                        this.scrollToCurrent()

                        if (! this.isPaused) {
                            this.startCycle(this.cycleDuration)
                        }
                    },

                    pause() {
                        if (this.isPaused || ! this.cycleStartTime) {
                            return
                        }

                        const elapsed = performance.now() - this.cycleStartTime

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

                        const start = this.cycleStartTime ?? performance.now()

                        const update = () => {
                            const elapsed = performance.now() - start
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
                            const container = this.$refs.adsContainer

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
                }
            })
        })
    </script>
@endif