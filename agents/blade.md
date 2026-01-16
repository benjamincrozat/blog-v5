## Blade

This file exists to keep Blade changes consistent and maintainable. Read this when you touch `resources/views/**`.

## Instructions

- HTML must be **tidy, valid, semantic, and accessible**.
- Prefer landmarks (`header`, `nav`, `main`, `footer`) over generic wrappers.
- Icons:
  - Decorative icons: `aria-hidden="true"`.
  - Informative icons: provide an accessible name.
- Top-of-file Blade comment blocks use:
  - `{{--` on its own line,
  - A capitalized sentence ending in a period,
  - `--}}` on its own line.
- Blade components in `resources/views/components/**` should start with a short comment block explaining purpose + contract.
- Avoid inline `@php(...)`; prefer `@php ... @endphp` blocks for non-trivial PHP.
- When an element has many attributes, format one per line for readability.
- Control structure like `@if ()`, `@for ()`, etc., have a space before the opening parenthesis.
