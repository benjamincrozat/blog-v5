---
name: laravel
description: Canonical Laravel app-layer architecture guidance.
metadata:
  short-description: Canonical Laravel architecture
---

# Laravel

## Scope

Use for app-layer Laravel changes: requests, controllers, actions, models, jobs, and notifications.

## Required Rules

- Keep class-level intent docblocks in PHP classes.
- Import namespaces, avoid ambiguous names, prefer guard clauses, and default to `protected` for non-public members.
- PHPStan note: `Collection` template types are invariant, so return annotations should match array-shape reality or stay broad enough to avoid covariance issues.
- Avoid `final` by default, never use `@` error suppression, and do not leave debug helpers (`dd`, `dump`, `var_dump`, etc.).
- If a method grows to roughly 15+ lines, consider extracting focused collaborators.
- For finite persisted domains, use backed enums, add casts, and compare with `$enum->value`.
- Prefer existing framework utilities first; for small uncovered features, write local code before adding packages.
- Third-party packages require strong justification and explicit user approval.
- Use Form Requests for validation and authorization.
- Keep controllers single-action, thin, and orchestration-focused.
- If a controller uses `$this->authorize(...)`, ensure `App\\Http\\Controllers\\Controller` uses `AuthorizesRequests`.
- Keep business logic in small verb-named actions (avoid generic Service/Manager/Handler naming unless justified).
- Keep jobs thin and idempotent; delegate business work to actions.
- Keep side effects explicit in the action flow.
- Do not introduce events/listeners/observers unless unavoidable.
- Prefer helpers/facades/`app()` for app-layer resolution rather than constructor injection in app code.
- Never call `env()` outside `config/*.php`.
- Prefer named routes and `route()` over hardcoded URLs.
- Prefer Eloquent/Query Builder over raw SQL; if raw SQL is unavoidable, parameterize it and document why.
- For repeated query/matching rules, centralize behavior in model scopes and/or dedicated actions instead of duplicating inline query fragments.
- Migrations must implement `down()`. Never edit old merged migrations; add a new migration.
- Seeders should assume local + fresh DB and prefer factories over direct `Model::create()` calls.
- When an action returns structured domain/presentation data, prefer explicit classes over associative arrays.
- Keep response/view/export serialization at boundaries (`toArray()` in controllers/resources/components), not deep in domain logic.
- During hard-cutover refactors, update all consumers in one pass (actions, controllers, commands, tests, and audits).
- If an action contract should stay primitive (`bool`, `int`, `string`, `null`), keep it primitive.
- Delegate Blade template and component conventions to `laravel-blade`.

## Workflow

1. Keep flow explicit: request/controller -> action (`handle(...)`) -> model/notification.
2. Preserve public behavior unless explicitly requested to change it.
3. Keep route names and URIs stable unless explicitly requested.
4. Add or update tests for each behavior change (follow `pest-feature-tests`).
5. Follow `delivery-standards` for quality gates and reporting.

## References

- `laravel-blade` for Blade templates/components.
- `delivery-standards` for documentation, validation, and commit policy.
