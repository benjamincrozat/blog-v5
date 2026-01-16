## Testing (Pest)

This file exists to keep the test suite reliable and parallel-safe.

## Commands

- **Run tests**: `php vendor/bin/pest --parallel`
- **Check coverage**: `php vendor/bin/pest --coverage --parallel`

## Instructions

- Prefer integration tests over unit tests.
- Prefer mirroring `app/` under `tests/Feature/App/**`.
- If there is no matching `app/` file, only then place tests at the root with a clear justification (e.g. `tests/Feature/**`).
- Import Pest globals explicitly, e.g.:
  - `use function Pest\Laravel\actingAs;`
- Avoid `$this` in Pest tests when a global helper exists.
- Any bug fix must add a regression test.
- Mocking is a last resort.
- Prefer strict fakes over permissive mocks (fail loudly when contracts change).
- Avoid shared fixed file paths.
- Clean up any files you create.
- Use `OpenAI::fake()` with `CreateResponse::fake()` and other testing utilities the package provides.