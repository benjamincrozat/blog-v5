<img src="https://github.com/user-attachments/assets/45c65ced-ba72-4de1-a047-7f2aa85f8e4a" width="100" />

# The modern hub for developers, by Benjamin Crozat

This is the source code for my revenue-generating 45K monthly visitors [developer hub](https://benjamincrozat.com).

**Feel free to steal whatever you need.** But first, why don't you follow me on [X](https://x.com/benjamincrozat)?

## What to expect

- **Independently built and maintained:**  
  This codebase evolves organically as time allows and needs arise.
  
- **Production-ready Laravel code:**  
  Explore clean, fast, and tested code with Actions, Jobs, Policies, and more.

- **Monetization & analytics:**  
  See how I implemented affiliate links, ad-blocker-resistant analytics, etc.

- **Automation & background jobs:**  
  See how I automate background operations with queues (managed by Horizon) and scheduled tasks.

- **Cloudflare Images integration:**  
  Because I didn't want to develop my own image upload flow from scratch and Cloudflare Images is pretty damn good.

- **Comprehensive test suite:**  
  350+ tests written using Pest show how to keep features reliable and code maintainable.

<img src="https://github.com/user-attachments/assets/3d1f2ca8-cfbf-458c-a451-9f093820361f" />

## Post workflow

Posts are file-managed from `resources/markdown/posts`.

Initial export from the current local database:

```bash
php artisan migrate
php artisan blog:export
```

Normal editing workflow:

```bash
php artisan blog:sync
```

1. Edit the Markdown file for the post.
2. Update `published_at` to publish, unpublish, or schedule it.
3. Run `php artisan blog:sync`.
4. Refresh the site or Filament list to confirm the synced result.

Notes:
- `blog:export` is for one-time migration or explicit regeneration.
- `blog:sync` validates every Markdown file before writing anything to the database.
- Unknown authors, unknown categories, duplicate IDs/slugs, and invalid front matter fail loudly.
- Filament is read-only for posts after the cutover.
- Deployment should run `php artisan blog:sync` before `php artisan app:sync-search-console-sitemap`.

## Search Console automation

This app supports automated sitemap submission through the Google Search Console API.

Primary command:

```bash
php artisan app:sync-search-console-sitemap
```

What it does:
- regenerates `public/sitemap.xml`
- submits that sitemap URL to Google Search Console when the integration is enabled and the app is running in production
- runs connection checks against the configured Google endpoints outside production instead of submitting anything

Configuration:
- Set `SEARCH_CONSOLE_ENABLED=true`.
- Set `SEARCH_CONSOLE_PROPERTY` to your Search Console property ID, such as `sc-domain:benjamincrozat.com` or `https://benjamincrozat.com/`.
- Leave `SEARCH_CONSOLE_SITEMAP_URL` empty to default to `APP_URL/sitemap.xml`, or set it explicitly if needed.
- Choose one auth method:
- OAuth refresh token: `SEARCH_CONSOLE_OAUTH_CLIENT_ID`, `SEARCH_CONSOLE_OAUTH_CLIENT_SECRET`, `SEARCH_CONSOLE_OAUTH_REFRESH_TOKEN`
- Service account: `SEARCH_CONSOLE_SERVICE_ACCOUNT_EMAIL`, `SEARCH_CONSOLE_SERVICE_ACCOUNT_PRIVATE_KEY`

Notes:
- Google’s general Indexing API is not for normal blog posts; this workflow uses the supported Search Console sitemap submission endpoint instead.
- If you use a service account, add that service account email to the Search Console property first.
- `blog:sync` never submits to Search Console; keep sitemap submission as an explicit separate step.
