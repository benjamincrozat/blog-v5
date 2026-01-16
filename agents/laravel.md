## Laravel

This file exists to keep Laravel edits consistent across the repo.

## Instructions

- Top-level namespaces: `Core`, `Marketing`, `Shared` (then feature sub-namespaces).
- Controllers: prefer single-action controllers; keep them thin (request orchestration only).
- Business logic: prefer small, verb-named Actions (avoid generic “Service/Manager/Handler” classes).
- Jobs: thin + idempotent; delegate business logic to Actions.
- Avoid events unless they materially simplify flow; default to explicit code paths.
- Do not use dependency injection in app code. Prefer helpers, facades, Real-Time Facades, or `app()`.
- Avoid assigning helper/facade/app() results to locals unless reused.
- Never call `env()` outside `config/*.php`.
- Prefer named routes + `route()` over hardcoded URLs (including in app code and tests).
- Prefer Eloquent/Query Builder over raw SQL. If raw SQL is unavoidable, parameterize and document why.
- Migrations:
  - Always implement the `down()` method.
  - Never edit old migrations once merged; create a new migration instead.
- Seeders:
  - Always assume they will only be ran locally.
  - Always assume the database starts fresh before running the seeders. No need to check for existing data.
  - Use factories instead of `Model::create()`. We need the fake data.
- If you create a Model, also create a Factory + Seeder (unless there’s a very good reason not to).

