---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/527efaf4-30dd-4066-8f8b-1012c05f904d
Title: "Disable \"packages are looking for funding\" in NPM"
Description: "Learn how you can disable the \"packages are looking for funding\" messages in your project or globally."
Published at: 2024-03-04
Categories: javascript
---

## Disable the message globally

When working with NPM (Node Package Manager), you might have encountered a message indicating that packages are looking for funding." To disable it, run the following command in your terminal:

```bash
npm config set fund false
```

## Disable the message in your project only

To disable funding messages for a single project, you can use the `.npmrc` file:

1. Navigate to your project's root directory.
2. Create or edit the `.npmrc` file.
3. Add the following line: `fund=false`

This setting tells NPM not to display funding messages for the project in question. Remember, this change only affects the current project, not your global NPM settings.

## Disable the message temporarily 

To disable funding messages temporarily, use the `--no-fund` option:

```bash
npm install --no-fund
```

This won't change any config value anywhere.

And by the way, the option also works for with the `update` command and a few others.

## Why are packages looking for funding?

Open-source projects are the backbone of the software development world, with thousands of developers relying on free, publicly available packages to build their applications. However, maintaining these packages requires time, effort, and resources. The _"packages are looking for funding"_ message is a way for open-source developers to seek financial support from the community. This funding helps maintain the quality, security, and development of these packages.

The funding model not only supports the developers but also ensures the longevity and reliability of the software you use. By contributing, you're helping sustain an ecosystem that thrives on collaboration and innovation.

Of course, financial stability is something that only a handful of us achieved. You cannot support everyone and disabling the _"packages are looking for funding"_ message is a legitimate thing to do.
