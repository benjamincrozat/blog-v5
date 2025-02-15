---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/50/speed_rxhuwj.png
Title: "Create a SPA in seconds using wire:navigate in Livewire v3"
Description: "Discover how to boost the speed of your Laravel apps, mimicking an SPA, without building an API, using Livewire v3 and the new wire:navigate attribute."
Published at: 2023-08-28
Categories: livewire, laravel
---

## Introduction

Have you noticed how speedy this blog is? This must be a SPA, right? Nope! This is thanks to the new HTML attribute `wire:navigate` in [Livewire v3](https://livewire.laravel.com/docs/navigate).

Traditional Laravel applications are multi-page and require a full browser page reload whenever the user clicks a link. Single Page Applications (SPAs), on the other hand, nix this approach for a nimbler one - instead of reloading the entire page, only part of it is refreshed. This eliminates the overhead of re-downloading JavaScript and CSS assets during every request, resulting in a significantly enhanced browsing experience.

Let's learn how it can bring us the speediness of SPAs without their complexity. In this tutorial, I promise, we won't have to write an API!

## How to use wire:navigate

Using `wire:navigate` is a breeze. Just add it as an attribute to your links:

```html
<nav>
    <a href="/" wire:navigate>Dashboard</a>
    <a href="/posts" wire:navigate>Posts</a>
    <a href="/users" wire:navigate>Users</a>
</nav>
```

Here's what happens when you click a link. Instead of the browser visiting the new page, Livewire v3 intercepts the request. It uses its own speedy method to retrieve and render the page, giving your app a noticeable boost.

## Using wire:navigate for redirects

You know those moments when you need to redirect users within your app? With `wire:navigate`, you can make these transitions smooth as butter. Here's how to do it in your Livewire component:

```php
return $this->redirect('/posts', navigate: true);
```

This tells Livewire v3 to use its nifty `wire:navigate` function to whisk users off to the new page - so quick, they'll hardly notice.

## Prefetch links with wire:navigate.hover

The secret sauce to `wire:navigate`'s speediness is all in the prefetching. As soon as a user presses down on their mouse, Livewire swings into action. By the time they lift their finger, the new page is ready and waiting.

And for even faster transition times, use the `.hover` modifier:

```html
<a href="/posts" wire:navigate.hover>Posts</a>
```

## Persist elements across pages with @persist

Want to keep parts of your UI consistent between page loads? Let Livewire v3 help with the `@persist` directive. It's perfect for things like audio or video players:

```html
@persist('player')
    <audio src="{{ $episode->file }}" controls></audio>
@endpersist
```

Your players will carry on playing uninterrupted - no matter how many pages your users browse.

## Update the page before navigating away

With `wire:navigate`, there's no need to risk losing whatever your user was doing. Just use the `livewire:navigating` event to run JavaScript before the page change and do what you have to do:

```javascript
document.addEventListener('livewire:navigating', () => {
    // Modify your HTML here
})
```

## Script evaluation in wire:navigate

Since content is replaced at every page view, you might want to use the event `livewire:navigated`, which will be called every time the DOM has been fully replaced.

`DOMContentLoaded`, which will only be called after the first page load, won't cut it in our case.

```diff
-document.addEventListener('DOMContentLoaded', () => { 
+document.addEventListener('livewire:navigated', () => { 
     // Initiate 3rd-party libraries here...
 });
```

## Reload pages when assets change using wire:navigate

To keep your JavaScript fresh post-deployment, add `data-navigate-track` to your `<script>` tags:

```html
<head>
    <script src="/app.js?id=123" data-navigate-track></script>
</head>
```

Updating the version hash will now force a full page reload, ensuring everyone gets the freshest script.

## Conclusion

By now, you're a pro at making your Laravel application "feel" like a speedy single page application with Livewire v3's `wire:navigate` attribute.

Remember, it's not about turning your application into an SPA - it's about borrowing the benefits.

Fast, seamless browsing is no longer reserved for JavaScript-heavies. With Livewire and `wire:navigate`, we have all the power we need! 🔥
