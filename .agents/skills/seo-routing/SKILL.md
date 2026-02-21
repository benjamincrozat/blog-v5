---
name: seo-routing
description: Implement and maintain SEO-safe routing and HTTP behavior for this blog.
metadata:
  short-description: SEO-safe routes, redirects, and crawl behavior
---

# SEO Routing

## Scope

Own route topology and crawler-facing HTTP behavior.

## Required Rules

- Preserve route surface unless explicitly changed:
  - `/`
  - `/categories`
  - `/categories/{slug}`
  - `/{slug}` (declared last)
  - `/feed`
  - `/sitemap.xml`
- Keep status semantics exact:
  - missing post: `404`
  - unpublished/future post: `404`
  - soft-deleted post: `410`
  - legacy slug redirect: `301`
- Use data-driven redirects (model/table + middleware).
- Preserve query strings on redirects.
- Keep canonical/meta behavior aligned in page templates when URL/status behavior changes.

## Workflow

1. Confirm route order and collision safety (`php artisan route:list`).
2. Keep slug redirect integrity when sync changes slugs.
3. Add/adjust feature tests for `404`/`410`/`301` behavior.
4. Regenerate sitemap when URL surface changes (`php artisan blog:generate-sitemap`).
5. Follow `delivery-standards` quality gates.

## References

- Pair with `laravel` for controller/request/action architecture.
- Pair with `pest-feature-tests` for route behavior coverage.
