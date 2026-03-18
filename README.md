<img src="https://github.com/user-attachments/assets/45c65ced-ba72-4de1-a047-7f2aa85f8e4a" width="100" />

# The code behind a blog that generated $20K+ and peaked at 100K monthly visits

This is the source code for [benjamincrozat.com](https://benjamincrozat.com), my content-driven developer blog and one of the main ways I market my work.

It has generated more than **$20,000 in revenue** and reached a peak of **100,000 visits per month**.

If you're checking me out as a developer, this repo shows how I build and run a real Laravel publishing business: content, monetization, automation, performance, and editorial tooling included.

**Feel free to borrow whatever is useful.** If you want to keep up with what I ship, follow me on [X](https://x.com/benjamincrozat).

## What you'll find inside

- **A real-world Laravel application:**  
  Clean, production-tested code with Actions, Jobs, Policies, queues, scheduled tasks, and the kind of decisions that come from running a site people actually use.

- **A content engine, not a demo project:**  
  Posts are managed in Markdown, synced into the app, enriched with images, and published through a workflow designed for consistent output.

- **Monetization and analytics patterns:**  
  Affiliate links, attribution, ad-blocker-resistant analytics, and the practical plumbing behind a site that earns revenue.

- **Automation and background processing:**  
  Queues, Horizon, and scheduled jobs that keep repetitive work off the critical path.

- **Cloudflare Images integration:**  
  A pragmatic image workflow for hero images and inline article assets without building a custom media system from scratch.

- **A serious testing mindset:**  
  A large Pest test suite that helps keep the publishing flow and business logic reliable as the site evolves.

<img src="https://github.com/user-attachments/assets/3d1f2ca8-cfbf-458c-a451-9f093820361f" />

## Post workflow

Posts are file-managed from `resources/markdown/posts`.

Initial export from the current local database:

```bash
php artisan migrate
php artisan app:export-posts
```

Normal editing workflow:

```bash
php artisan app:sync-posts
```

1. Edit the Markdown file for the post.
2. Upload the hero image and any inline article images to Cloudflare Images.
3. Update `published_at` to publish, unpublish, or schedule it.
4. Run `php artisan app:sync-posts`.
5. Refresh the site or Filament list to confirm the synced result.

Image upload workflow:

```bash
# Hero image: uploads the file and updates image_disk/image_path in the post
php artisan app:upload-post-image /absolute/path/to/cover.png --markdown=your-post.md

# Inline image: uploads the file and prints a Markdown snippet you can paste
php artisan app:upload-post-image /absolute/path/to/step.png --alt="Describe the screenshot"
```

Notes:
- `app:upload-post-image` always uploads to the `cloudflare-images` disk.
- Use the returned URL for inline article images.
- When `--markdown` is passed, the command updates `image_disk` and `image_path` in the Markdown file. Run `php artisan app:sync-posts` afterward.

Notes:
- `app:export-posts` is for one-time migration or explicit regeneration.
- `app:sync-posts` validates every Markdown file before writing anything to the database.
- Unknown authors, unknown categories, duplicate IDs/slugs, and invalid front matter fail loudly.
- Filament is read-only for posts after the cutover.
- Deployment should run `php artisan app:sync-posts` before `php artisan app:sync-search-console-sitemap`.

## Search Console automation

This app supports automated sitemap submission through the Google Search Console API.

Primary command:

```bash
php artisan app:sync-search-console-sitemap
```

What it does:
- regenerates `public/sitemap.xml`
- submits that sitemap URL to Google Search Console in production when credentials and the property are configured
- runs connection checks outside production instead of submitting anything
- verifies the configured credentials and property access read-only outside production when credentials are present

Configuration:
- There is no enable flag. This becomes active when credentials and the property are configured.
- Set `SEARCH_CONSOLE_PROPERTY` to your Search Console property ID, such as `sc-domain:benjamincrozat.com` or `https://benjamincrozat.com/`.
- Leave `SEARCH_CONSOLE_SITEMAP_URL` empty to default to `APP_URL/sitemap.xml`, or set it explicitly if needed.
- Choose one auth method:
- OAuth refresh token: `SEARCH_CONSOLE_OAUTH_CLIENT_ID`, `SEARCH_CONSOLE_OAUTH_CLIENT_SECRET`, `SEARCH_CONSOLE_OAUTH_REFRESH_TOKEN`
- Service account: `SEARCH_CONSOLE_SERVICE_ACCOUNT_EMAIL`, `SEARCH_CONSOLE_SERVICE_ACCOUNT_PRIVATE_KEY`

Notes:
- Google’s general Indexing API is not for normal blog posts; this workflow uses the supported Search Console sitemap submission endpoint instead.
- If you use a service account, add that service account email to the Search Console property first.
- `app:sync-posts` never submits to Search Console; keep sitemap submission as an explicit separate step.
