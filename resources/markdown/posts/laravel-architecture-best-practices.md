---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/56/programmer_v_02_o9k1tl.jpg
Title: "3 crucial Laravel architecture best practices for 2024"
Description: "Explore Laravel's default architecture to simplify collaboration, compatibility, and onboarding."
Published at: 2023-09-01
Modified at: 2024-03-15
Categories: laravel
---

## Introduction to architecturing Laravel projects

How should you organize your Laravel app to best serve your needs? **Well, the good news is that you don't have to worry about this since you are using a framework! Stick to the defaults unless you have good and objective reasons to do otherwise.**

And yet, people can't stop overthinking the architecture of their projects.

To me, it seems that the urge to deviate from the standard project structure often reveals a deeper issue - a fundamental inability to maintain organization. Whether you adhere to the Laravel architecture or significantly modify it, the outcome is likely to be disorganized.

Therefore, to address this problem, we will put ourselves in shoes that would fit almost any enterprise project.

Before we begin, though, let's define what an "enterprise project" is in our context. Essentially, it's a public facing project with lots of users that generates revenue, making it vital to continuously evolve by adapting to new technologies, business requirements, and market trends.

Here's what is expected from the team of such projects:
1. **Easy collaboration.**
2. **Maximize compatibility with third-party solutions** that will help maintain the cost of development down.
3. **Keep the cost of onboarding low.** To achieve this, new hires need to easily find their way around the codebase, which can make them somewhat productive even when they lack domain knowledge.

With these goals in mind, let's dive into what I, and most of the experts from the community, think are the best architecture practices.

**Oh and before you continue, if you think you really need to go beyond what Laravel offers because you are working on big projects, let me introduce you to alternative ways of structuring applications taught by Martin Joe in his books "[Microservices with
Laravel](/recommends/microservices-laravel)" and "[Domain-Driven Design with Laravel](/recommends/domain-driven-laravel)."**

## Laravel architecture best practices

### Keep the default folder structure

**Using Laravel is meant to make your life easier, not harder.**

1. First, following conventions helps ensure that new hires can quickly find everything they need and start being productive as soon as possible. Laravel is a popular framework, and most developers will already be familiar with its default folder structure. By sticking to this, you help minimize the learning curve for new team members.
2. Also, a profitable project is supposed to last for many years. People come and go. You will likely move on to something else. Why wouldn't you make it easy for the ones who will take over?
3. Additionally, by following the framework's defaults, you ensure compatibility with many first and third-party packages. This can be crucial for maintaining development costs down and maximizing the use of available resources.

### Organize by domain without breaking the folder structure

While it's essential to keep the default folder structure, it's also necessary to organize your code in a way that makes sense for your project.  One way to do this is by organizing it by domain, without breaking the default folder structure.

This means that, for example, inside your *Models* folder, you could create a *Blog* folder. This way, when using the `php artisan make:model Blog/Category` command, the new file will be created at the right place.

This approach can also be used for controllers, middlewares, policies, and so on. Organizing your code the intended way will help you maintain a compatible, clean and intuitive codebase.

### Don't reach out for a package when Laravel already has a solution

Developers love discovering new ways of doing things, and it's always tempting to experiment with new packages or approaches. This is fine for personal projects or when you are working alone, but it may not be ideal in a team setting.

When you hire Laravel developers, you are hiring them to expand and maintain your product using Laravel. It's essential to remember this and stick to the built-in features of Laravel whenever possible.

For example, don't use Data Transfer Objects (DTOs) instead of custom form requests unless there are good and objective reasons to do so. Using the built-in features of Laravel ensures that all developers on your team are working with the same set of tools and reduces the learning curve for new hires.

## Don't take my word for it, listen to the other experts

Matt Stauffer, who has a lot of experience building apps for enterprise as the CEO of Tighten, talks about how keeping things simple benefits big projects.

<iframe src="https://www.youtube.com/embed/KBigS5vLwZk?si=M4tVBih9-T7YRb7N" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>

James Brooks is a core Laravel team member. He knows what working with a big team and a big codebase are. He also asked me to include it in this article, so there he is!

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Want to know my #1 Laravel tip?<br><br>Follow the standards laid out by the skeleton and framework 👏<br><br>You&#39;ll find:<br><br>- Updates easier<br>- More packages will &quot;just work&quot;™<br>- New developers will grok your project quicker<br>- Life is just more enjoyable 🏄</p>&mdash; James Brooks (@jbrooksuk) <a href="https://twitter.com/jbrooksuk/status/1697182125663945015?ref_src=twsrc%5Etfw">August 31, 2023</a></blockquote>

Sebastian Schlein is the co-founder of BeyondCo, a company deeply involved with Laravel, and he also thinks that you should stick to the framework's defaults. This is a tweet from 2019 by the way.

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">⚡️Why you should stick to the default <a href="https://twitter.com/laravelphp?ref_src=twsrc%5Etfw">@laravelphp</a> architecture <a href="https://t.co/wfQ1Xl9eh4">https://t.co/wfQ1Xl9eh4</a></p>&mdash; Sebastian Schlein (@seb_sebsn) <a href="https://twitter.com/seb_sebsn/status/1186228940555345921?ref_src=twsrc%5Etfw">October 21, 2019</a></blockquote>

Jason McCreary, from Laravel Shift, also showcases his favorite way of organizing Laravel projects. Looks familiar, don't you think?

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">My ideal Laravel folder structure. Effortlessly elegant. 👀 <a href="https://t.co/h0UYrnc6Xy">pic.twitter.com/h0UYrnc6Xy</a></p>&mdash; Jason McCreary (@gonedark) <a href="https://twitter.com/gonedark/status/1333474208123412488?ref_src=twsrc%5Etfw">November 30, 2020</a></blockquote>

All that being said, at the end, results matter the most. Here's a tweet from Taylor Otwell himself about keeping an open mind:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">I try not to express very strong opinions on how people structure or build their Laravel applications.<br><br>I want to keep a strong culture of exploration and experimentation in the ecosystem. 👩‍🔬</p>&mdash; Taylor Otwell ☁️ (@taylorotwell) <a href="https://twitter.com/taylorotwell/status/1668580181504606208?ref_src=twsrc%5Etfw">June 13, 2023</a></blockquote>
