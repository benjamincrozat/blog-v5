---
id: "01KKEW276BPAN5RQ1AFGS4Y4VD"
title: "A quick look at the PHP match expression"
slug: "a-quick-look-at-the-php-match-expression"
author: "benjamincrozat"
description: "Learn how PHP's match expression improves strict comparisons, multi-case arms, match(true), and enum pairing for cleaner code."
categories:
published_at: 2025-09-30T06:15:00+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: null
image_path: null
sponsored_at: null
---
If you still reach for switch, this post by Ashley Allen is a nice refresher on why match can be cleaner.

Key bits I liked:
- match uses strict comparison (===), so types must match.
- You can stack multiple cases in one arm.
- match(true) lets you write simple rule checks.
- It pairs well with enums and can be exhaustive without a default.

A tiny taste:

```php
return match ($driver) {
    'github', 'self-hosted' => new GitHubDriver(),
    'gitlab'                 => new GitLabDriver(),
    'bitbucket'              => new BitbucketDriver(),
    default                  => throw new InvalidArgumentException(),
};
```

I found the enum example especially useful for turning stored values into friendly labels. If you are on PHP 8.0 or higher, this is an easy win for tidy code.

If cleaner control flow is the thing you want more of in PHP, these next reads take that idea into a few other useful language features:

- [Use enums when strings and constants start getting brittle](/php-enums)
- [See what PHP 8.5 changes before you upgrade](/php-85)
- [Make PHP serialization finally click without the usual confusion](/a-friendly-guide-to-php-serialization-that-finally-clicked)
- [Understand exceptions before your next try/catch block](/php-exceptions)
