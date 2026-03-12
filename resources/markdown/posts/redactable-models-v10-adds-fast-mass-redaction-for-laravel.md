---
id: "01KKEW27MY94AV9VZ73NKHR5KE"
title: "Redactable Models v1.0 adds fast mass redaction for Laravel"
slug: "redactable-models-v10-adds-fast-mass-redaction-for-laravel"
author: "benjamincrozat"
description: "Redactable Models v1.0 brings mass redaction to Laravel, plus Laravel 12 and PHP 8.4 support. Quick way to bulk redact sensitive data in Eloquent models."
categories:
published_at: 2025-09-23T16:38:05+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: null
image_path: null
sponsored_at: null
---
If you handle user data in Laravel, Ashley Allen just shipped Redactable Models v1.0 with a neat upgrade. The package now supports mass redaction, so you can wipe sensitive fields in bulk with a single SQL update. That means less memory use and way faster runs on big tables.

It plugs into Eloquent with simple interfaces, and you can set rules like “redact users older than 30 days.” v1.0 also updates support to Laravel 12, PHP 8.4, and PHPUnit 12.

I like tools that make privacy chores simple. If you need GDPR friendly cleanup without dropping rows, this is worth a look.

If you are thinking about privacy work as part of everyday Laravel maintenance now, these are the next reads I would open:

- [Close the Laravel security gaps that are easy to miss](/laravel-security-best-practices)
- [Use soft deletes without getting tripped up later](/laravel-soft-deletes)
- [Decide whether compromised-password checks belong in your auth flow](/block-compromised-password)
- [Protect your API with Laravel Sanctum before it gets exposed](/laravel-sanctum-api-tokens-authentication)
