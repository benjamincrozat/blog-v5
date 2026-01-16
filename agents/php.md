## PHP

This file exists because naming and “why-this-file-exists” documentation are high-leverage in a fast-moving codebase.

## Instructions

- Every class/trait/interface/enum under `app/` must have a **top-level PHPDoc block** explaining:
  - Why the file exists.
  - Why the logic was extracted there (vs inlining).
  - What callers should rely on (the “contract”) when it’s non-obvious.
- Always import namespaces.
- Avoid one-letter or ambiguous names unless extremely local and obvious.
- Prefer guard clauses / early returns over deep nesting.
- Default to `protected` for non-public methods/properties unless there’s a strong reason.
- Avoid `final`.
- Never use the PHP error suppression operator `@`. Prefer explicit alternatives (pre-checks, try/catch, etc.). If it’s truly unavoidable, document why.
- Never leave debugging helpers in the codebase (`print_r()`, `var_dump()`, etc.).
- Don’t repeat obvious context in variable names (keep qualifiers only when they disambiguate).
- Prefer intention-revealing names that describe outcomes, not implementation details.
- If a method is ~15+ lines, consider splitting into smaller methods.
- Enums:
  - Finite domain value sets should be backed enums.
  - Persisted finite sets should have model casts.
  - When comparing in queries or external payloads, use `$enum->value`.