## Alpine.js (bundled with Livewire v3)

This file exists to keep Alpine.js usage consistent. Read this when you add `x-` directives in Blade or coordinate Alpine.js with Livewire.

## Instructions

- Keep Alpine.js state small and local to the component.
- Use `x-cloak` to avoid flashes during init.
- Keep ARIA attributes in sync with state (e.g. `aria-expanded`).
- Do not install/import/start Alpine.js separately. Livewire v3 bundles Alpine.js and its plugins; double-loading causes “multiple instances of Alpine.js” conflicts.
