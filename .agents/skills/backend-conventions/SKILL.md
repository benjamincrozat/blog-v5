---
name: backend-conventions
description: Backend conventions for Livewire v4, Laravel, PHP, dependencies, testing, and project structure.
---

# Backend conventions

## When writing PHP code

- Every class/trait/interface/enum under `app/` has a top-level PHPDoc: why it exists, why it was extracted, and (when non-obvious) its contract.
- Import namespaces; avoid ambiguous names; prefer guard clauses; default to `protected` for non-public.
- PHPStan note: `Collection` template types are invariant, so prefer return annotations that match the actual array-shape, or keep them broad enough to avoid covariance issues.
- Avoid `final`; never use `@` error suppression; don’t leave debug helpers (`dd`, `dump`, `var_dump`, etc.).
- If a method is ~15+ lines, consider splitting it.
- Enums: use backed enums for finite domains; add casts for persisted values; compare using `$enum->value`.
- Never do useless things like that. Accessing directly from the helper doesn't hold any performance penalty. Here's a bad example:
    ```php
    /** @var User $user */
    $user = request()->user();

    return view('foo', [
        'user' => $user,
    ]);
    ```

    Do this instead:

    ```php
    return view('foo', [
        'user' => request()->user(),
    ]);
    ```

## When deciding whether to use a third-party package or write the code yourself

- Prefer existing utilities/framework features.
- For small features the framework doesn't cover, write code instead of adding a third-party package.
- Using a third-party package requires strong justification and **user approval**.

## When writing Laravel code

- Controllers: single-action and thin (orchestration only).
- If controllers call `$this->authorize(...)`, ensure `App\Http\Controllers\Controller` uses `AuthorizesRequests`.
- Business logic: small, verb-named Actions (avoid “Service/Manager/Handler” unless it makes sense).
- For organization/clinic/call access checks, prefer route scoped bindings + policy methods; avoid manual ownership checks inside controllers when policy/binding can express the same rule.
- For scoped-denial behavior that should look like missing resources, prefer policy responses with `Response::denyAsNotFound()`.
- Jobs: thin + idempotent; delegate to Actions.
- Avoid events unless they materially simplify flow.
- Don’t use dependency injection in app code; prefer helpers, facades, Real-Time Facades, or `app()`.
- Never call `env()` outside `config/*.php`.
- Prefer named routes + `route()` over hardcoded URLs.
- Prefer Eloquent/Query Builder over raw SQL; if raw SQL is unavoidable, parameterize and document why.
- Migrations: always implement `down()`. Never edit old migrations once merged—add a new one.
- Seeders: assume local + fresh DB; use factories (not `Model::create()`); if you create a Model, also create a Factory + Seeder (unless there’s a very good reason not to).

## Action contract and model-class patterns

- When an Action returns structured domain/presentation data (especially consumed by multiple controllers/jobs), prefer explicit classes over associative arrays.
- For non-Eloquent contract classes in this project, place them under `app/Models/Core` and avoid the `*Model` suffix.
- Keep response/view/export compatibility by serializing at boundaries (`toArray()` in controllers/resources/components), not deep inside domain logic.
- If a query/matching rule is reused (e.g., clinic-call ownership or inbound filters), centralize it with model scopes and/or a dedicated action before adding another inline query.
- During hard-cutover refactors, update all consumers in one pass: actions, controllers, commands, tests, and audits.
- If an action should remain primitive (e.g., `bool`, `int`, `string`, `null`), keep it primitive; no wrapper class needed.

## When writing Blade views

- Don't put any logic in Blade views other than **presentation logic**.
- Use class-based components for views that contain complex **presentation logic**.
- Don't use properties to set classes on components.
- Instead, use `$attributes->class('')` to apply classes.
- When classes have to be applied conditionally, use an array of classes:
    ```blade
    $attributes->class([
        'foo bar' => $someCondition,
        'baz' => $anotherCondition,
    ])
    ```
- If a Blade component needs users to style **specific internal elements** (e.g. a dropdown trigger vs panel), prefer **named slots** for those parts and let users apply classes in the slot markup, rather than adding `*Class` / `*Classes` props.

## When creating dynamic and reactive interfaces

- Use Livewire and Alpine.js (that is directly bundled with Livewire)
- Livewire automatically injects its JavaScript and CSS. No need to define styles for `x-cloak` for instance.
- Prefer conventions over boilerplate (view auto-discovery; attributes like `#[Computed]` when they clarify).
- Full-page components: prefer `Route::livewire('/path', Component::class)`.
- Component tags must be closed: use `<livewire:foo />` (not unclosed `<livewire:foo>`).
- Wrapper `wire:model` needs child events? use `wire:model.deep="..."`.
- Navigation: prefer `$this->redirect*()` with `navigate: true`; use `wire:navigate` and `wire:navigate:scroll` when needed; use `@persist` for UI that must survive navigation.
- Loading UX: prefer Tailwind `data-loading:*`; use lazy/defer + placeholders for expensive regions.
- Performance islands: prefer `@island` with `lazy` / `defer` / `bundle`, paired with `@placeholder`.
- Transitions: `wire:transition` uses View Transitions; modifiers like `.opacity` / `.duration.*` are removed in v4.
- JS: prefer interceptors (`Livewire.interceptMessage` / `Livewire.interceptRequest`) over deprecated hooks.
- Docs: if behavior is unclear, consult official v4 docs (web search when needed), starting at `https://livewire.laravel.com/docs/4.x/upgrading`.

## When writing tests using Pest

- Prefer Feature Tests.
- Mirror `app/` under `tests/Feature/App/**`.
- It's mandatory to write tests for class-based Blade components.
- Import Pest globals explicitly; avoid `$this` at all costs unless you can't do otherwise.
- Any bug fix must add a regression test.
- Mocking is a last resort; prefer strict fakes; avoid fixed shared file paths; clean up files you create.
- For action contract refactors, assert typed return objects and key serialized parity (`->toArray()`), not only class existence.
- For command tests where helper return typing is ambiguous, prefer `Artisan::call(...)` + `Artisan::output()` assertions.
- When paginating Eloquent models, avoid replacing paginator collections with a different item type; prefer model attributes for paginated views and typed row classes for non-paginated/export flows.
