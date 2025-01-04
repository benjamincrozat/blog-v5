---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/19/forge_yb2qea.png
Title: "Laravel Forge: price, review, how-to and alternatives (2024)"
Description: "Choose a cloud hosting provider for Laravel Forge and deploy your next Laravel project quickly and without any DevOps cost."
Commercial: true
Published at: 2022-11-17
Modified at: 2024-01-21
Categories: laravel, tools, web-hosting
---

## Introduction to Laravel Forge

*[Laravel Forge](https://forge.laravel.com) is a service that automatically provisions optimized PHP servers **using the cloud hosting provider of your choice**.*

Be ready to save a lot of time and focus on building!

You will find below the best cloud providers that work well with Laravel Forge, as well as the best alternatives. (It's good to keep an open mind!)

## Is Laravel Forge free?

No, Laravel Forge isn't free.

Luckily, it offers a 5-day free trial for you to review it.

That being said, you will still need to subscribe to a cloud hosting provider (and I hand-picked some of the best for you).

## Laravel Forge pricing

![Laravel Forge pricing](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/104/conversions/Screenshot_2023-01-26_at_01.21.16_m9yt2j-medium.jpg)

### The monthly price of Laravel Forge

| Plan     | Price         |
| -------- | ------------- |
| Hobby    | $12 per month |
| Growth   | $19 per month |
| Business | $39 per month |

**Forge's hobby plan has everything you can ask for**.

Unless you need to use multiple servers, **this is the way to go**. 

The only critical thing missing for me would be database backups, but you can set this up yourself with [spatie/laravel-backup](https://packagist.org/packages/spatie/laravel-backup) for instance.

**The growth plan is the perfect balance of price and features** because you can manage multiple servers, which is very useful when your web applications need to handle a lot of traffic.

The business plan is perfect if you're actually running a business. $39 is a fair price. **This plan will let you set up database backups directly from the UI and is compatible with pretty much any storage provider (S3, DigitalOcean Spaces, etc.).**

### The annual price of Laravel Forge

| Plan     | Price         |
| -------- | ------------- |
| Hobby    | $120 per year |
| Growth   | $199 per year |
| Business | $399 per year |

The annual plans let you save 17%, which is a no brainer for people running critical Laravel applications.

## Key features of Laravel Forge

- Logs viewer;
- Edit your *.env* file;
- Create Nginx redirections;
- Effortless tasks scheduler;
- Share projects with employees;
- Deploy a new website in minutes;
- **Free 1-click SSL certificates**;
- Monitor your server and send alerts;
- **Install multiple versions of PHP**;
- Trigger deployment when after a `git push`;
- Provision highly optimized and secure PHP web servers;
- **Automatic database backups** (S3, DigitalOcean Spaces, etc.).

## What I like about Laravel Forge

- The UI and UX are top-notch!
- Laravel Forge has been created by Taylor Otwell himself and is actively maintained by proficient developers he handpicked. You can expect frequent updates and great support.

## What I dislike about Laravel Forge

- Backups are walled behind the business plan unlike [Cloudways](#cloudways) and [Ploi](#ploi).
- No VPS resizing option. Something [Cloudways](#cloudways) does, which allows you to not have to go to the providers' dashboard.
- No zero-downtime deployments unless you pay for Envoyer. Forge has been updated to faciliate the link with Envoyer, so that's nice, but you still have to pay a separate subscription. [Ploi](#ploi) includes this in one of their packages.

## How to get started with Laravel Forge

1. [Create an account](https://forge.laravel.com/auth/register) and subscribe to the desired plan (**you get a 7-day free trial no matter what**);
2. Subscribe to a cloud hosting provider (you will find my recommendations in this article, such as [Vultr](https://benjamincrozat.com/recommends/vultr), my favorite);
3. Connect your provider to Laravel Forge (this is where having root access over SSH comes in handy 👍);
4. Deploy your application ([the documentation](https://forge.laravel.com/docs/1.0/introduction.html) will help you get started).
5. If you're still lost and prefer video tutorials, Laracasts also has a [free course for Laravel Forge](https://laracasts.com/series/learn-laravel-forge-2022-edition).

![How to get started with Laravel Forge](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/105/conversions/Screenshot_2023-01-26_at_01.23.35_m6c3hc-medium.jpg)

## Cloud hosting providers for Laravel Forge by price

Being cost-efficient is important. Here, I sorted every hosting provider by price.

| Provider | Lowest monthly price |
| -------- | -------------------- |
| [Vultr](https://benjamincrozat.com/recommends/vultr) | $2.50 |
| [DigitalOcean](https://benjamincrozat.com/recommends/digitalocean) | $4 |
| [Hostgator](https://benjamincrozat.com/recommends/hostgator) | $23.95 | No |

## Alternatives to Laravel Forge by price

The great thing with the alternatives to Laravel Forge is that they also provide the hosting. This can lower the cost and simplify bookkeeping.

| Provider | Lowest monthly price |
| -------- | -------------------- |
| [Ploi](https://benjamincrozat.com/recommends/ploi) | €8 (+ hosting) |
| [Cloudways](https://benjamincrozat.com/recommends/cloudways) | $10 |
| Laravel Forge | $12 (+ hosting) |
| [Kinsta](https://benjamincrozat.com/recommends/kinsta) | $20 |

## The best cloud hosting providers for Laravel Forge

Discover **my top picks of cloud providers fit to be used along with Laravel Forge**.

**I also list alternative services to Laravel Forge**, which some of them are lower cost.

### DigitalOcean

![DigitalOcean](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/106/conversions/www.digitalocean.com__sctfdo-medium.jpg)


[DigitalOcean](/recommends/digitalocean) has been a fantastic companion for Laravel Forge for me.

Its droplets, which are essentially Linux-based virtual machines, are full-featured and can be set up in seconds.

This makes it incredibly easy and efficient to manage my cloud infrastructure as I can interact with my droplets via an intuitive UI.

Additionally, the predictable monthly pricing and 99.99% uptime SLA give me peace of mind as I don’t have to worry about unexpected costs or downtime.

With various options available, like Premium CPU-Optimized, Memory-Optimized, and Storage-Optimized droplets, I can choose the right plan based on my workload and the demands of the applications I'm working on. Plus, the free outbound data transfer, monitoring, and firewalls make it a cost-effective solution.

Lastly, the team management feature has been invaluable for collaborating on projects while keeping everything secure. 

Overall, combining Laravel Forge with [DigitalOcean](/recommends/digitalocean) has streamlined my development workflow and allowed me to focus on writing code rather than managing infrastructure.

[Try DigitalOcean](/recommends/digitalocean)

### Vultr

![Vultr](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/107/conversions/www.vultr.com__akl39r-medium.jpg)

[Vultr](/recommends/vultr) is especially handy for developers as it allows instant deployment worldwide, offering a vast array of OS combinations and no long-term contracts.

This flexibility meant I could scale up and down on demand and only pay for what I used.

Also, the fact that it runs atop best-in-class AMD and Intel CPUs, with an optional upgrade to NVMe SSD, ensured I had incredible performance at an unbeatable price.

Although I'm not using [Vultr](/recommends/vultr) anymore, I appreciated its powerful add-ons and user-friendly control panel and API that allowed me to spend more time coding and less time managing my infrastructure.

To conclude, it was a great experience using [Vultr](/recommends/vultr) with Laravel Forge, as it offered flexibility, performance, and ease of management essential for any developer.

[Try Vultr](/recommends/vultr)

### HostGator

![HostGator](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/108/conversions/CleanShot_2023-05-09_at_13.45.53_2x_hivzzy-medium.jpg)

The VPS hosting plans of [HostGator](/recommends/hostgator) are incredibly flexible and come with a full suite of features that developers, like myself, find very useful.

The full root access allowed me to install any necessary software and the unrestricted access to create unlimited email addresses, databases, and FTP accounts made it a breeze to manage my projects. Plus, the weekly off-site backups can be life saving.

While I've moved on to other solutions that better fit my current needs, I can definitely vouch for the reliability and performance of [HostGator](/recommends/hostgator)'s VPS hosting, especially when used alongside Laravel Forge.

It's a reliable solution for developers looking to deploy and manage their applications with ease.

[Try HostGator](/recommends/hostgator)

## The best alternatives to Laravel Forge

### Ploi

[![Ploi](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/193/conversions/DUqP0CeRV2vPCh3xT5F6oO9CwFpMO3-metaQ2xlYW5TaG90IDIwMjMtMTAtMTIgYXQgMDUuMDkuNDlAMngucG5n--medium.jpg)](/recommends/ploi)

[Ploi](/recommends/ploi) is an alternative to Laravel Forge that drastically cut cost for the average person and small businesses. Let's face it: it started as a clone. But using it, you quickly realize it's more than that. It's easy to use and in some areas, it even does better than Forge.

And I also appreciate that you get some features using the middle pricing tier that would require you to pay a premium on Forge + a separate subscription for Envoyer.

Currently, Ploi is the service I use to manage the DigitalOcean Droplet (VPS) of this blog. I don't have a lot of negative things to say about it. They have tons of features that make your life so convenient. I never needed their support yet, but I only heard good things about it so far.

[Try Ploi](/recommends/ploi)

### Cloudways

![Cloudways](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/109/conversions/Screenshot_2022-11-01_at_21.03.04_yaljg2-medium.jpg)


[Cloudways](/recommends/cloudways) is an excellent alternative to Laravel Forge for several reasons.

First and foremost, it takes away all the deployment hassles and provides ready-made solutions for effortless application deployment.

Unlike Forge, [Cloudways](/recommends/cloudways) comes with advanced hosting features that make application management significantly easier.

For instance, the 1-click Laravel install feature reduces the developer's load to install apps manually.

Then, we have other features that Forge also has like pre-configured optimization tools such as PHP-FPM, Supervisord, and Redis, which greatly improve the Laravel app performance.

[Cloudways](/recommends/cloudways) also offers a dedicated IP address and SSD-based hosting, ensuring powerful and consistent performance.

The advanced cache technologies, optimized stack, and Cloudflare integration also contribute to fast content delivery and superior performance.

Plus, the 24/7 expert support and free migration service are invaluable add-ons.

All these features, combined with the transparent and affordable pricing plans, make [Cloudways](/recommends/cloudways) a robust and reliable hosting solution for Laravel applications.

[Try Cloudways](/recommends/cloudways)

### Kinsta

![Kinsta](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/110/conversions/kinsta.com_application-hosting__otz1pu-medium.jpg)

[Kinsta](/recommends/kinsta) is a Platform as a Service (PaaS) hosting provider that also is an alternative to Laravel Forge, and offers fast, secure, and scalable web application hosting.

With over 55,000 developers and digital entrepreneurs, Kinsta offers 25 locations across five continents that offer the fastest Google C2 machines running on Google’s Premium Tier network.

They provides a developer-focused feature set with support for popular languages and frameworks.

[Kinsta](/recommends/kinsta) offers internal connections, dedicated databases, and a choice of data center locations for maximum speed.

Their simple and good looking dashboard provides an easy way to deploy and manage apps, with unlimited users and support for developers by developers.

The support staff is made up of experts, developers, and engineers that provide fast and multilingual support.

[Kinsta](/recommends/kinsta) offers resource-based pricing, so you only pay for what you use, making it simple, transparent, and predictable (quite costly, though).

[Try Kinsta](/recommends/kinsta)

## Free alternatives to Laravel Forge: are there any?

Unfortunately, it seems **there are no worthy free alternative to Forge**.

Remember: **if something that costs money to a company is free, it means that YOU are the product.**

However, if you are ready to make compromises, you could host static websites (meaning it's just HTML, CSS, and JavaScript) for free on these awesome services:
- Cloudflare pages
- Deta.sh
- DigitalOcean App Platform
- Fly.io
- GitHub Pages
- GitLab Pages
- Google Firebase Hosting
- Netlify
- Railway
- Render
- Surge
- Vercel
