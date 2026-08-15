---
id: "01KKEW27CEKPSEC2VH2X09RZWS"
title: "Laravel Forge pricing and alternatives"
slug: "laravel-forge"
author: "benjamincrozat"
description: "Compare current Laravel Forge pricing, the real server cost behind each plan, Laravel VPS, Laravel Cloud, managed hosting, and panel alternatives."
categories:
  - "laravel"
  - "tools"
  - "web-hosting"
published_at: 2022-11-16T23:00:00Z
modified_at: 2026-08-15T09:28:36Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: true
image_disk: "cloudflare-images"
image_path: "images/posts/BZxE5aJJALmGSft.png"
sponsored_at: null
---
Laravel Forge costs **$12, $19, or $39 per month**. That subscription manages servers; it is not normally the server bill itself.

| Plan | Monthly | Annual | Server allowance |
| --- | ---: | ---: | --- |
| Hobby | $12 | $120 | 1 external server and unlimited Laravel VPS servers |
| Growth | $19 | $199 | Unlimited servers |
| Business | $39 | $399 | Unlimited servers |

Those are the prices on the official Forge page as I update this guide. Laravel VPS usage, an external cloud server, managed databases, storage, and backups can add separate charges.

[See current Laravel Forge pricing](https://forge.laravel.com/pricing)

## What Laravel Forge actually does

Forge provisions and manages a server for you while leaving you in control of that server. It handles the repetitive Laravel operations:

- server provisioning and PHP setup
- sites, domains, SSL certificates, and environment variables
- Git deployments and deployment scripts
- queue workers, scheduled jobs, and daemons
- database and backup controls
- monitoring and team access
- zero-downtime deployments for new compatible sites

You still own server-level decisions such as capacity, regions, operating-system maintenance, database layout, and what happens when the machine is unhealthy. Forge reduces server work; it does not turn a VPS into a fully managed platform.

That distinction is the real buying decision.

## What each Forge plan changes

### Hobby: one external server or Laravel VPS

Hobby is enough when you have one DigitalOcean, Hetzner, AWS, or other external server. It also allows unlimited Laravel VPS servers, whose usage is billed through Forge.

The plan includes unlimited sites and deployments, zero-downtime deployments, hosted Forge domains, monitoring, community support, and server sharing.

### Growth: unlimited servers and standard support

Growth removes the one-external-server limit. It also adds standard support and a 5% Laravel VPS discount.

This is the practical plan once one production server, one worker, one database server, and staging can no longer fit on the Hobby allowance.

### Business: support and a larger VPS discount

Business keeps unlimited servers, adds advanced support, and increases the Laravel VPS discount to 15%.

It is not a different deployment product. The upgrade makes sense when response time and a larger Laravel VPS bill justify the extra subscription cost.

## Calculate the real monthly cost

Do not compare Forge's $12 subscription with the full price of a managed host. Compare the complete stack:

| Setup | What you pay for | Who handles the server? |
| --- | --- | --- |
| Forge + external VPS | Forge plan + provider server + optional backups and services | You, with Forge automating routine work |
| Forge + Laravel VPS | Forge plan + hourly Laravel VPS usage | You, with provisioning and billing consolidated in Forge |
| Laravel Cloud | Plan fee when applicable + usage | Laravel Cloud |
| Managed Laravel host | The host's quoted plan and add-ons | The hosting company |
| Self-managed VPS | Server, backups, monitoring, and your time | You |

For example, if your chosen VPS costs $12, a $12 Hobby subscription makes the starting stack $24 before paid backups, databases, object storage, and traffic. A managed option can cost more on paper and still be cheaper if it removes work you do not want to own.

My [Laravel hosting comparison](/best-laravel-hosting-providers) walks through the full deployment decision rather than ranking providers by their cheapest headline price.

## Forge versus Laravel Cloud

Forge and Laravel Cloud solve different problems:

| Question | Forge | Laravel Cloud |
| --- | --- | --- |
| Do you manage a server? | Yes | No |
| Do you get SSH and operating-system control? | Yes | No traditional server to manage |
| Pricing model | Flat Forge plan plus infrastructure | Usage, plus $20 Growth or $200 Business plan when selected |
| Scaling | You size and arrange servers | Platform features include hibernation and higher-tier autoscaling |
| Best fit | You want control without repeating setup by hand | You want to deploy Laravel without owning server operations |

Laravel Cloud Starter is pay-as-you-go. Growth is $20 per month plus usage, and Business is $200 per month plus usage. Always put expected compute, workers, databases, storage, and traffic into the [official Cloud calculator](https://cloud.laravel.com/pricing/calculator) before comparing totals.

Choose Forge when root access, a specific provider, custom services, or predictable server architecture matters. Choose Cloud when removing infrastructure work matters more than server control.

## Forge versus Cloudways or another managed host

[Cloudways](/recommends/cloudways) bundles hosting and a management layer. That can mean fewer vendors and more help with the underlying server, but also less control than a VPS you manage through Forge.

The questions I would ask are concrete:

1. Can I run long-lived Laravel queue workers?
2. Can I configure cron, Redis, WebSockets, and process supervision?
3. Who patches the operating system and database?
4. Are backups included, and has restore been tested?
5. Can I SSH in and change the web-server configuration?
6. What happens when traffic doubles?
7. What is the full price after storage, backups, and bandwidth?

If the host cannot answer those for your exact plan, the word “managed” is not enough.

## Forge versus Ploi

[Ploi](/recommends/ploi) is the closest style of alternative in this list: a control panel that provisions and manages servers you choose.

Compare the workflows you will use every week—deployment scripts, queue workers, site isolation, database backups, team permissions, monitoring, and support—not a long feature checklist. Either panel can be inexpensive compared with the time saved, so the better choice is usually the one your real deployment fits with fewer exceptions.

## When I would choose Forge

Forge is a strong fit when:

- I want normal VPS access and can diagnose Linux, Nginx, PHP-FPM, queues, and databases.
- I run several sites on one server and want a flat management bill.
- I need a provider or region that a managed platform does not offer.
- I want deployment automation without giving up the machine underneath it.

I would choose a more managed option when nobody wants to own patching, capacity, backups, monitoring, or incidents. I would self-manage only when the unusual infrastructure requirement is worth losing Forge's time savings.

The shortest answer is: Forge buys automation and control, not freedom from servers. Price that complete responsibility before choosing it.
