import.meta.glob([
    '../img/**',
    '../svg/**',
])

import { Alpine, Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm'
import Autosize from '@marcreichel/alpine-autosize'

Alpine.plugin(Autosize)

Livewire.start()
