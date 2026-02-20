---
name: pest-feature-tests
description: Write and maintain Pest tests for this Laravel blog with a feature-first strategy.
metadata:
  short-description: Feature-first Pest testing conventions
---

# Pest Feature Tests

## Scope

Use when adding, updating, or reviewing tests.

## Required Rules

- Prefer feature tests unless true unit isolation is required.
- Mirror `app/` paths under `tests/Feature/App/**` for new tests.
- Add a regression test for every bug fix.
- Tests for class-based Blade components are mandatory.
- Prefer strict fakes over mocks.
- Use mocks only when no fake or real collaborator works cleanly.
- Keep filesystem tests isolated with unique temp paths and cleanup.
- Use explicit Pest helper imports and prefer helper-based calls over `$this->...` style.
- For action-contract refactors, assert typed return objects and key serialized parity (`->toArray()`), not only class existence.
- For command tests where helper return typing is ambiguous, prefer `Artisan::call(...)` plus `Artisan::output()` assertions.
- When paginating Eloquent models, avoid replacing paginator collections with different item types; keep model-backed pagination for views and use typed rows for non-paginated/export flows.

## Workflow

1. Reproduce behavior in a failing or targeted feature test.
2. Implement or refactor with deterministic collaborators (`Notification::fake()`, `Mail::fake()`, etc.).
3. Assert explicit outcomes (status, payload, DB side effects, counts).
4. Keep tests focused on observable behavior and avoid brittle internals.
5. Follow `delivery-standards` quality gates.

## References

- Pair with `laravel` for architecture-driven testing changes.
