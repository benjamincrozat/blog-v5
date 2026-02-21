---
name: rendering-accessibility
description: Implement and maintain post rendering, syntax highlighting, and accessible semantic output behavior for this blog.
metadata:
  short-description: Markdown rendering, highlight, and accessible output
---

# Rendering Accessibility

## Scope

Own presentation behavior for post and archive rendering.

## Required Rules

- Use the central renderer (`App\\Markdown\\MarkdownRenderer`) for post body rendering.
- Keep Tempest Highlight CommonMark extension integrated for fenced and inline code.
- Preserve semantic, accessible output structure.
- Keep highlighted token contrast and code block readability intact.
- For sample content fixtures, use realistic fictional topics, distribute markdown feature coverage, include representative code fences/inline code, and avoid benchmark labels unless requested.
- Avoid unsolicited visual redesign.

## Workflow

1. Change renderer/highlight integration in minimal slices.
2. Verify rendered output remains predictable and accessible.
3. Validate highlight readability after style changes.
4. Follow `delivery-standards` quality gates and reporting.

## References

- `laravel-blade` for Blade template/component conventions.
