---
id: "01KKEW279517CRXQBTK9N8QFSW"
title: "How to generate Laravel Factories using ChatGPT"
slug: "generate-laravel-factories-chatgpt"
author: "benjamincrozat"
description: "Discover how to harness AI for rapid Laravel Factory generation, saving days of manual coding in massive codebases. Done smart, done right!"
categories:
  - "ai"
  - "gpt"
  - "laravel"
published_at: 2023-08-29T00:00:00+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/7mRpeXX9NQr2BvN.png"
sponsored_at: null
---
## Introduction

Generating quality code using a Large Language Model such as GPT requires a basic understand of the technology. And you can quickly learn about it here: [How do language-based AIs, such as GPT, work?](/gpt-llm-ai-explanation)

That being said, you could also follow this tutorial, copy and paste my prompts, and be done with it!

Before I forget, **I recommend using GPT-4 for better results**, as it's way smarter than GPT-3.5. Also, remember there's a lot of randomness and consistency accross prompts cannot not be ensured. That being said, the time you save will make up for it!

## The problem we want ChatGPT to solve

So, what problem are we trying to solve here?

During my freelance career, **I stumbled upon a lot of codebases that weren't leveraging Laravel Factories at all**. That's a bummer, because they can help you:
1. Write tests with randomized inputs for your code.
2. Set up a good local environment filled with generated data.

**In a big codebase, there may be dozens of models, and writing factories for each of them all by yourself could take days of hard work.**

Unless we leverage the power of AI, right?

## The prompt to generate Laravel Factories using ChatGPT

By asking ChatGPT to think step by step and detail its reasoning, we can ensure better quality answers. But first, the requirements:
1. The model's table schema.
2. The model's code.

```text
The model's table schema: <the model's table schema>

The model's code: <the model's code>

Goal: Use the information above to generate a Laravel Factory.

Instructions:
* Don't include attributes that are automatically handled by Laravel.
* Faker no longer recommends calling properties. Instead, call methods. For instance, "$this->faker->paragraph" becomes "$this->faker->paragraph()".
* Include a method for each many-to-many relationship using factory callbacks.

Review each of my instructions and explain step by step how you will proceed in an existing Laravel installation without using Artisan. Then, show me the result.
```