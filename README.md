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
- Deployment should run `php artisan blog:sync` before sitemap generation.
