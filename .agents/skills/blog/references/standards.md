# Standards

## Product Standards

- Keep SEO-safe URL behavior stable.
- Preserve existing route semantics and post visibility rules.
- Avoid regressions in status codes (`404`, `410`, `301`).

## Content Standards

- Markdown files are the source of truth.
- Frontmatter contract must stay strict and explicit.
- Category input is YAML list and normalized safely.

## Rendering Standards

- Use centralized Markdown renderer.
- Keep Tempest Highlight extension wired into Markdown conversion.
- Keep highlighted code readable in theme CSS.

## UI Standards

- Use semantic HTML.
- Keep markup accessible.
- Apply `wire:navigate` for internal links.
- Avoid unsolicited visual redesign.

## Testing Standards

- Prefer failing tests as signal; fix root cause.
- Add/adjust tests with behavior changes.
- Keep tests deterministic and focused on observable outcomes.
