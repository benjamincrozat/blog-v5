@props(['ads' => []])

<div
    {{
        $attributes
            ->class('group bg-white/75 fixed bottom-2 sm:bottom-4 group-hover inset-x-2 sm:right-auto sm:left-1/2 sm:-translate-x-1/2 group backdrop-blur-md rounded-b-sm rounded-t-md shadow-xl sm:w-[560px] backdrop-saturate-200 overflow-hidden ring-1 ring-black/10')
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
    @toggle-sticky-carousel.window="showcase()"
    @force-sticky-carousel.window="forceShowcase($event.detail)"
>
    <div class="py-1 px-2.5 flex gap-2 items-center">
        <div class="grow"></div>

        <a
            wire:navigate
            href="{{ route('advertise') }}"
            class="p-1 -mr-1 bg-black/4 transition-colors hover:bg-black/7.5 rounded-md"
            data-pirsch-event="Clicked sticky carousel info"
            data-pirsch-meta-destination="Advertise"
        >
            <x-heroicon-o-question-mark-circle class="size-4" />
            <span class="sr-only">Become a sponsor</span>
        </a>

        <button
            class="p-1 -mr-1.5 bg-black/4 transition-colors hover:bg-black/7.5 rounded-md"
            type="button"
            @click="hide()"
            data-pirsch-event="Closed sticky carousel"
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
                <x-ads.bottom.item />
            </template>
        </div>
    </template>
    
    <template x-if="ads.length">
        <div class="block h-1.25 bg-linear-to-r from-transparent to-blue-600/30" x-bind:style="progressStyle()"></div>
    </template>
</div>

<script>
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
                hasBeenShown: false,
                trackedAdViews: [],

                init() {
                    if (! Array.isArray(this.ads) || ! this.ads.length) {
                        return
                    }

                    if (this.isDismissed()) {
                        this.show = false
                    }

                    this.$watch('currentIndex', () => this.scrollToCurrent())
                    this.$watch('show', (visible) => {
                        if (visible && this.canCycle()) {
                            this.startCycle(this.cycleDuration)
                        }

                        if (visible) {
                            this.trackShow()

                            return
                        }

                        this.stopCycleCompletely()
                    })

                    this.scrollToCurrent()
                },

                startCycle(duration = this.cycleDuration, elapsedOffset = 0) {
                    if (! this.canCycle()) {
                        return
                    }

                    this.isPaused = false
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
                    if (! this.canCycle()) {
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

                hide() {
                    this.trackEvent('Closed sticky carousel')

                    this.show = false
                    this.dismissedUntil = Date.now() + 24 * 60 * 60 * 1000
                    this.stopCycleCompletely()
                },

                showcase() {
                    if (this.isDismissed() || this.show) {
                        return
                    }

                    this.show = true
                },

                forceShowcase(detail = null) {
                    const customAds = this.extractAds(detail)

                    if (customAds?.length) {
                        this.ads = customAds
                        this.currentIndex = 0
                        this.trackedAdViews = []
                        this.hasBeenShown = false

                        this.stopCycleCompletely()
                        this.scrollToCurrent()
                    }

                    this.dismissedUntil = null

                    if (! this.show) {
                        this.showcase()

                        return
                    }

                    if (this.canCycle()) {
                        this.startCycle(this.cycleDuration)
                    } else {
                        this.stopCycleCompletely()
                    }
                },

                isDismissed() {
                    if (! this.dismissedUntil) {
                        return false
                    }

                    return Date.now() < this.dismissedUntil
                },

                stopCycleCompletely() {
                    this.clearCycleTimeout()
                    this.stopProgressAnimation()
                    this.progress = 0
                    this.cycleStartTime = null
                    this.remainingCycleDuration = this.cycleDuration
                    this.isPaused = true
                },

                elapsedSinceCycleStart() {
                    if (! this.cycleStartTime) {
                        return 0
                    }

                    return performance.now() - this.cycleStartTime
                },

                canCycle() {
                    return Array.isArray(this.ads) && this.ads.length > 1
                },

                trackShow() {
                    if (this.hasBeenShown) {
                        return
                    }

                    this.hasBeenShown = true

                    this.trackEvent('Sticky carousel shown', {
                        ads: this.ads.length,
                    })
                },

                trackAdView(ad, index) {
                    if (this.trackedAdViews.includes(index)) {
                        return
                    }

                    this.trackedAdViews.push(index)

                    this.trackEvent('Sticky carousel ad shown', {
                        title: ad?.title ?? '',
                        position: (index ?? 0) + 1,
                    })
                },

                trackAdClick(ad) {
                    this.trackEvent('Sticky carousel ad clicked', {
                        title: ad?.title ?? '',
                        url: ad?.url ?? '',
                    })
                },

                trackEvent(eventName, meta = null) {
                    if (typeof pirsch !== 'function') {
                        return
                    }

                    if (meta && Object.keys(meta).length) {
                        pirsch(eventName, { meta })

                        return
                    }

                    pirsch(eventName)
                },

                extractAds(detail) {
                    if (! detail) {
                        return null
                    }

                    let ads = detail.ads ?? detail

                    if (typeof ads === 'string') {
                        try {
                            ads = JSON.parse(ads)
                        } catch (_) {
                            return null
                        }
                    }

                    if (! Array.isArray(ads)) {
                        return null
                    }

                    return ads
                        .map((ad) => ({
                            icon: ad.icon ?? '',
                            title: ad.title ?? '',
                            description: ad.description ?? '',
                            cta: ad.cta ?? '',
                            url: ad.url ?? '#',
                        }))
                        .filter((ad) => ad.title || ad.description || ad.cta || ad.icon)
                },
            }
        })
    })
</script>