# Checklists

## Change Checklist

1. Inspect code paths (`rg`, `sed`, route list, model/service files).
2. Implement minimal coherent slice.
3. Write or update tests for changed behavior.
4. Validate affected behavior.
5. Commit with sentence-style message.

## Validation Checklist

- Follow `AGENTS.md` validation commands.
- Run impacted framework/project commands.
- For content-only changes, run the project's content-sync command (for example, the command that ingests source posts/pages).
- For route changes, verify with `php artisan route:list`.

## Documentation Checklist

- Class-level intent docblock present in modified/new PHP classes.
- Top-level Blade intent comment present in modified/new views.
- Comments explain system intent, not obvious syntax.

## Git Checklist

- Follow `AGENTS.md` git workflow and commit policy.
- Avoid destructive git commands unless requested.
