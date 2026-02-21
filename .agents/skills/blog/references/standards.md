# Standards

## Product Standards

- Keep SEO-safe URL behavior stable.
- Preserve existing route semantics and publication visibility rules.
- Avoid regressions in status codes (`404`, `410`, `301`).

## Content Standards

- Source content files are the source of truth.
- Frontmatter/content metadata contracts must stay strict and explicit.
- Category/tag input should be normalized safely.

## Rendering Standards

- Use a centralized Markdown/content renderer.
- Keep syntax highlighting extensions wired into content conversion.
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
