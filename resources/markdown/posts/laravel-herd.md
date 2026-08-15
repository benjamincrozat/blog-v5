---
id: "01KKEW27CG49GRV02BM10BPV0S"
title: "Laravel Herd: install it on macOS or Windows"
slug: "laravel-herd"
author: "benjamincrozat"
description: "Install Laravel Herd on macOS or Windows, verify PHP and local sites, switch global or per-project PHP versions, and fix common path and HerdHelper issues."
categories:
  - "laravel"
  - "tools"
published_at: 2023-07-18T22:00:00Z
modified_at: 2026-08-15T09:28:36Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K6DQVFEZX3FNMC12QNBH9A0N.png"
sponsored_at: null
---
Laravel Herd is a native PHP and Laravel development environment for macOS and Windows. Install one app and you get PHP, Nginx, DNS for `.test` sites, Composer, the Laravel installer, and Node version management.

[Download Laravel Herd](https://herd.laravel.com/download)

| Requirement | Current minimum |
| --- | --- |
| macOS | macOS 12 or newer |
| Windows | Windows 10 or newer, with administrator access during setup |
| Linux | Herd is not available |

I verified the commands and requirements below against Herd's current macOS and Windows documentation. Herd is not installed on this workstation, so I am not presenting local speed or compatibility claims as test results.

## Install Herd on macOS

1. Download the DMG from the official Herd site.
2. Drag Herd into Applications.
3. Open Herd and complete onboarding.
4. Approve the admin prompt for the background service that runs Nginx and DNS.
5. Let Herd download its current PHP build.

Herd parks `~/Herd` by default. A project at `~/Herd/my-app` becomes available at `http://my-app.test` without a manual virtual host.

Verify the installation:

```bash
herd --version
php --version
composer --version
laravel --version
node --version
```

Fish users need to add Herd's binaries to their path:

```bash
fish_add_path -U $HOME/Library/Application\ Support/Herd/bin/
```

## Install Herd on Windows

1. Download the Windows installer.
2. Run it with administrator privileges.
3. Let setup install the HerdHelper service.
4. Open Herd and finish onboarding.
5. Put projects in `%USERPROFILE%\Herd` when you want automatic `.test` domains.

Verify it in PowerShell or Command Prompt:

```powershell
herd --version
php --version
composer --version
laravel --version
node --version
```

HerdHelper updates the Windows hosts file and maps linked or parked projects to `.test` domains. That service is the first thing to inspect when PHP works in the terminal but local domains do not resolve.

## Create or link a local site

Create a project inside the parked folder:

```bash
cd ~/Herd
laravel new my-app
```

On Windows, start in `%USERPROFILE%\Herd` instead.

For a project elsewhere on the machine, open its directory and link it:

```bash
cd /path/to/my-app
herd link
```

Add a trusted local HTTPS certificate with:

```bash
herd secure
```

Herd detects Laravel's `public` directory. If a non-Laravel PHP project needs a different document root, check its site driver in Herd's Site Manager.

## Manage PHP versions

Herd currently offers PHP 7.4 through PHP 8.5 and installs the latest stable branch by default.

List, install, update, and select PHP versions from the CLI:

```bash
herd php:list
herd php:install 8.4
herd php:update 8.5
herd use 8.5
```

`herd use` changes the global version for sites that are not isolated.

Give one project its own version from that project's directory:

```bash
cd ~/Herd/legacy-app
herd isolate 8.2
herd php -v
herd unisolate
```

List every isolated site with:

```bash
herd isolated
```

Herd's PHP patch builds can arrive a few days after an official PHP release because they are compiled and tested for Herd. A missing brand-new patch is not automatically a broken updater.

## Check which PHP Herd actually uses

These commands answer slightly different questions:

| Command | What it checks |
| --- | --- |
| `php -v` | The first PHP binary in the shell path |
| `herd php -v` | Herd's global or isolated PHP for the current directory |
| `herd which-php` | The exact Herd PHP binary serving the current site |
| `herd ini` | The config file for the relevant Herd PHP version |

If `php -v` and `herd php -v` disagree, the shell is finding another PHP installation first. Use my [PHP version diagnostic guide](/check-php-version) to compare the CLI, browser, binary, and loaded `php.ini` instead of changing random installations.

After editing a Herd `php.ini`, restart the services so web requests pick up the change:

```bash
herd restart
```

## Common Herd problems

### The terminal uses the wrong PHP

Run:

```bash
herd which-php
herd php -v
which -a php
```

The last command is for macOS. On Windows, use `where.exe php`. Fix the shell path or keep using Herd's proxied `herd php` and `herd composer` commands inside isolated projects.

### One site uses the wrong PHP version

Open the project's directory and isolate it:

```bash
herd isolate 8.4
herd which-php
```

The global version does not override an isolated site.

### A Windows .test domain does not resolve

Open an elevated terminal and inspect HerdHelper:

```powershell
sc.exe query HerdHelper
sc.exe start HerdHelper
```

If the service is missing, rerun the Herd installer. Herd's troubleshooting guide also points to `%USERPROFILE%\.config\herd\bin\HerdHelper.exe` when you need its direct logs.

### Herd is slow on Windows

The official Windows guide recommends excluding `%USERPROFILE%\.config\herd` from Windows Defender scanning. Follow the current Herd instructions so the exclusion targets only its configuration and binaries.

### A new PHP patch is not listed yet

Run `herd php:update 8.5`, then check Herd's update page. Current patch releases can lag for a few days while Herd's builds are tested.

## Move from Laravel Valet to Herd

On first launch, Herd detects an existing Valet installation and can migrate its linked sites, parked paths, certificates, and settings. It asks Valet to stop but does not rewrite the Valet installation, so you can quit Herd and run `valet start` if you need to switch back.

I would still verify every important site before removing any old setup:

1. open its `.test` URL
2. check the isolated PHP version
3. check the loaded extensions and `php.ini`
4. test database and cache connections
5. test HTTPS and local callbacks

## Herd versus Valet versus Sail

| Choose | When it fits |
| --- | --- |
| Herd | You want a native macOS or Windows app, bundled tools, a GUI, and easy PHP switching |
| [Valet](/laravel-valet) | You use macOS, prefer a small CLI-first Homebrew setup, and do not need Herd's GUI |
| Sail | You want Docker because container parity and isolated services matter more than native speed |

Herd is the easiest default for a new macOS or Windows Laravel setup. Sail is the better answer when the project depends on a specific container topology rather than only PHP, Nginx, and local services.

## Herd Basic and Pro pricing

Herd Basic is free and includes the local PHP environment. Herd Pro is $99 for one year and adds integrated dumps, mail, logs, services, and debugging tools. A Pro key can be active on two devices.

Herd Teams is $299 for ten Pro licenses. If you do not renew, the app drops back to Basic; the current site says purchases have a 14-day refund period.

Check the [current Herd page](https://herd.laravel.com/) before buying because software pricing and included Pro tools can change.

Related guides:

- [Install Laravel Valet on macOS](/laravel-valet)
- [Install Laravel on macOS](/laravel-installation-macos)
- [Check the active PHP version and binary](/check-php-version)
- [Check the latest Laravel version and support status](/laravel-versions)
