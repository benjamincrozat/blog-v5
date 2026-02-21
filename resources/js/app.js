import.meta.glob([
    '../img/**',
    '../svg/**',
])

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm'
import Autosize from '@marcreichel/alpine-autosize'

Alpine.plugin(Autosize)

const scrollToHashTarget = () => {
    const hash = window.location.hash

    if (! hash || '#' === hash) {
        return
    }

    const target = document.getElementById(decodeURIComponent(hash.slice(1)))

    if (! target) {
        return
    }

    target.scrollIntoView()
}

const syncHashNavigation = () => {
    for (const delay of [0, 120, 300, 700]) {
        window.setTimeout(() => {
            window.requestAnimationFrame(scrollToHashTarget)
        }, delay)
    }
}

document.addEventListener('DOMContentLoaded', syncHashNavigation)
document.addEventListener('livewire:navigated', syncHashNavigation)

Livewire.start()
