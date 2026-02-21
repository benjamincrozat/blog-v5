---
name: livewire
description: Canonical Livewire v4 conventions for reactive interfaces in Laravel projects.
metadata:
  short-description: Canonical Livewire v4 reactive UI conventions
---

# Livewire

## Scope

Use for Livewire component behavior, navigation, loading states, and Livewire v4-specific patterns.

## Required Rules

- Use Livewire v4 for server-driven reactive interfaces and keep Alpine-specific state guidance in `alpine-js`.
- Prefer conventions over boilerplate (view auto-discovery and attributes like `#[Computed]` when they clarify intent).
- For full-page components, prefer `Route::livewire('/path', Component::class)`.
- Component tags must be closed (`<livewire:foo />`, not `<livewire:foo>`).
- If wrapper `wire:model` must react to child events, use `wire:model.deep="..."`.
- For navigation, prefer `$this->redirect*()` with `navigate: true`; use `wire:navigate` and `wire:navigate:scroll` when needed.
- Use `@persist` for UI elements that should survive Livewire navigation.
- Prefer Tailwind `data-loading:*` utilities plus lazy/defer placeholders for expensive UI regions.
- Prefer `@island` with `lazy` / `defer` / `bundle`, paired with `@placeholder`, for performance islands.
- `wire:transition` uses View Transitions in v4; do not use removed modifiers like `.opacity` or `.duration.*`.
- Prefer Livewire interceptors (`Livewire.interceptMessage`, `Livewire.interceptRequest`) over deprecated hooks.
- Livewire injects its JavaScript and CSS automatically; avoid extra bootstrap boilerplate for built-in directives.
- If behavior is unclear, consult official Livewire v4 docs first: `https://livewire.laravel.com/docs/4.x/upgrading`.

## Workflow

1. Keep rendered behavior stable unless explicitly requested to change it.
2. Apply Livewire conventions with the smallest coherent change slice.
3. Pair with `alpine-js` for local browser state and ARIA sync.
4. Follow `delivery-standards` quality gates and reporting.

## References

- Pair with `laravel-blade` for Blade structure and component organization.
- Pair with `alpine-js` for local interactive state rules.
