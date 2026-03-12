---
id: "01KKEW27BH5XE8W9QNGFDDA1AW"
title: "Laravel and Traefik dynamic config for easy multi-domain setups"
slug: "laravel-and-traefik-dynamic-config-for-easy-multi-domain-setups"
author: "benjamincrozat"
description: "Use Laravel plus Traefik’s HTTP provider to auto-manage multi-domain routing with YAML. Short, practical pattern inspired by Daniel Petrica."
categories:
published_at: 2025-10-05T12:14:00+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: null
image_path: null
sponsored_at: null
---
I just read a short guide by Daniel Petrica on using Laravel with Traefik to handle many domains without hand-editing configs.

The idea is simple:
- Let Laravel build Traefik rules on the fly.
- Point Traefik’s HTTP provider at a secure Laravel endpoint.
- Serve YAML instead of JSON for smooth reloads.

Traefik provider sample:

```
# traefik.yml
providers:
  http:
    endpoint: "https://admin.example.com/internal/traefik_config"
    pollInterval: "15s"
    headers:
      customRequestHeaders:
        X-Traefik-Passphrase: "yoursecrettoken"
```

Laravel response sample using Symfony YAML:

```
use Symfony\Component\Yaml\Yaml;

return response(
    content: Yaml::dump($config, 10),
    status: 200,
    headers: ['Content-Type' => 'text/yaml']
);
```

Why I like it:
- No giant static file to babysit.
- Add or remove domains in your app and Traefik follows after the next poll.
- Works great with Docker and a single service.

If you run Laravel behind Traefik and juggle many domains, this is a clean, low-friction pattern. Daniel’s write-up is short and practical, and it gave me a few ideas for my next deploy.

If you are still shaping the infrastructure around a multi-domain Laravel deploy, these are the next reads I would keep open:

- [See a production-ready Traefik and FrankenPHP setup for Laravel](/a-production-ready-laravel-setup-with-traefik-and-frankenphp)
- [Compare hosting options before you deploy another Laravel app](/best-laravel-hosting-providers)
- [See whether Laravel Forge still fits the way you deploy](/laravel-forge)
- [See how to deploy a PHP or Laravel app on Sevalla step by step](/deploy-php-laravel-apps-sevalla)
