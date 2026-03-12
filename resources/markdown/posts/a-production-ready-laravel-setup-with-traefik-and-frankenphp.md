---
id: "01KKEW2768VBNK3N08XSX22MWM"
title: "A production-ready Laravel setup with Traefik and FrankenPHP"
slug: "a-production-ready-laravel-setup-with-traefik-and-frankenphp"
author: "benjamincrozat"
description: "A clear Laravel production blueprint using Docker, Traefik, and FrankenPHP, explained by Daniel Petrica with tips for HTTPS, routing, caching, and zero downtime."
categories:
published_at: 2025-10-15T06:17:06+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: null
image_path: null
sponsored_at: null
---
Looking to take Laravel to production with Docker? This guide by Daniel Petrica shows a clean path using Traefik as the reverse proxy and FrankenPHP to run PHP fast and lean.

It walks through a simple, repeatable setup. You get HTTPS, routing, and zero downtime deploys. Caching and queues are covered too, so your app stays quick under load.

I liked how the services are split, and how health checks and logs fit in. It is easy to follow, even if you are new to Traefik or FrankenPHP.

Here is a tiny taste of the wiring with Traefik labels:

```yaml
services:
  app:
    image: dunglas/frankenphp
    labels:
      - "traefik.enable=true"
      - "traefik.http.routers.app.rule=Host(`example.com`)"
      - "traefik.http.routers.app.entrypoints=websecure"
      - "traefik.http.routers.app.tls.certresolver=letsencrypt"
```

If you want a practical blueprint for Docker, Laravel, Traefik, and FrankenPHP, this piece is a great start. It helps you ship with confidence, without a ton of guesswork.

If you are moving from local confidence to something you can run in production without flinching, these next reads help with hosting and deployment decisions:

- [Compare hosting options before you deploy another Laravel app](/best-laravel-hosting-providers)
- [See how to deploy a PHP or Laravel app on Sevalla step by step](/deploy-php-laravel-apps-sevalla)
- [See whether Laravel Forge still fits the way you deploy](/laravel-forge)
- [The 6 best PHP cloud hosting providers for 2024](/best-cloud-hosting-provider-php)
