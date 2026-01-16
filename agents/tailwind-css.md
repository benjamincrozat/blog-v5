## Tailwind CSS v4

This file exists to keep Tailwind CSS usage consistent and avoid bespoke CSS drift. Read this when you touch `resources/css/**` or Tailwind classes in Blade.

## Instructions

- Prefer Tailwind utilities over custom CSS.
- Extract repeated UI patterns into Blade components instead of copy/pasting long class strings.
- If custom CSS is necessary, keep it minimal and document why.
- Prefer Tailwind v4’s CSS-first setup:
  - Use `@theme` for design tokens (colors, fonts, radii, etc.).
  - Use `@utility` for small reusable utilities that should participate in variants/responsiveness.
  - Use `@custom-variant` / `@variant` only when you truly need custom variants.
- Prefer `size-*` over separate `w-*` + `h-*` when you mean a square.
