# Checklists

## Change Checklist

1. Inspect code paths (`rg`, `sed`, route list, model/service files).
2. Implement minimal coherent slice.
3. Write or update tests for changed behavior.
4. Validate affected behavior.
5. Commit with sentence-style message.

## Validation Checklist

- For code changes, run `./vendor/bin/pint`.
- For code changes, run `php artisan test`.
- Run impacted artisan commands.
- For Markdown/content-only changes, run `php artisan blog:sync` (no `pint` / test run required).
- For route changes, verify with `php artisan route:list`.

## Documentation Checklist

- Class-level intent docblock present in modified/new PHP classes.
- Top-level Blade intent comment present in modified/new views.
- Comments explain system intent, not obvious syntax.

## Git Checklist

- Use granular commits.
- Avoid destructive git commands unless requested.
- Do not push.
