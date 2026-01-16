## Livewire v4

This file exists to keep Livewire changes consistent and low-boilerplate. Read this when you touch `app/Livewire/**` or `resources/views/livewire/**`.

## Instructions

- When unsure about behavior, **web-search the Livewire v4 docs first** and follow the official guidance:
  - Prefer `livewire.laravel.com/docs/4.x/...` pages (upgrade guide, directives, events, JavaScript, etc.)
  - Start from the upgrade guide for gotchas: `https://livewire.laravel.com/docs/4.x/upgrading`
- **Prefer conventions over boilerplate**:
  - Omit `render()` when view auto-discovery applies.
  - Prefer attributes (`#[Title]`, `#[Computed]`, etc.) when they make the flow obvious.
- **Implicit model binding only requires to type hint a public property**. No need to bind it in the `mount()` method.
- **Routing**: Prefer `Route::livewire('/path', Component::class)` for full-page components (v4 recommended).
- **Component tags must be closed**: Always use a closed/self-closing tag (`<livewire:foo />`), never an unclosed `<livewire:foo>`.
- **`wire:model` on container elements**: v4 ignores bubbled child events by default; if you intentionally bind on a wrapper that needs child events, use `wire:model.deep="..."`.
- **Navigate + persistence**
  - Use `$this->redirect()`, `$this->redirectRoute()`, etc. instead of `redirect()`. Set the `navigate` parameter to `true` to have SPA-like behavior.
  - Use `wire:navigate` on links.
  - If preserving scroll inside a scrollable container across `wire:navigate`, use `wire:navigate:scroll` (not `wire:scroll`).
  - Consider `@persist` for elements that must survive navigation (nav, player, chat widget).
- **Loading UX**:
  - Prefer Tailwind `data-loading:*` variants for loading states on request-triggering elements (disable buttons, show opacity).
  - Use lazy/defer + placeholders for expensive sections (components or islands).
- **Islands (performance)**:
  - Use `@island` to isolate expensive regions so they re-render independently.
  - Use `lazy`, `defer`, and `bundle` to control when/how islands load; pair with `@placeholder` for skeleton UI.
- **Async actions**: Use `.async` or `#[Async]` for non-blocking actions (analytics/logging) to keep UI responsive.
- **Transitions**: `wire:transition` uses the View Transitions API in v4; **modifiers are removed** (`wire:transition.opacity`, `.duration.*`, etc.).
- **JavaScript (interceptors)**: Prefer `Livewire.interceptMessage` / `Livewire.interceptRequest` over deprecated `Livewire.hook('commit')` / `Livewire.hook('request')`.
- **CSP-safe mode**: If the app needs strict CSP (no `unsafe-eval`), enable `csp_safe` and keep directive expressions simple.
