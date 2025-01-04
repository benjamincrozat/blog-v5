---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/16/robot_qxeqid.png
Title: "Use PHP to leverage OpenAI's API and GPT effortlessly"
Description: "Improve your projects by leveraging the power of GPT, the famous language model, using PHP and OpenAI's REST API."
Published at: 2022-10-27
Modified at: 2023-11-07
Categories: php, gpt, ai
---

## Introduction to using the OpenAI API with PHP

To use OpenAI's REST API, you can either:
1. Use the API directly.
2. Or use a client written in PHP that will significantly simplify your journey.

Option #2 is precisely what we're aiming for, thanks to the unofficial [OpenAI PHP client](https://github.com/openai-php/client) written by Nuno Maduro and Sandro Gehri.

**This package is usable in any kind of PHP project.** No matter what your favorite framework or CMS is (Symfony, CodeIgniter, CakePHP, WordPress, Magento, etc.), there's something here for you.

And a [**Laravel adapter (openai-php/laravel) is available**](https://github.com/openai-php/laravel), which adds a handy facade and mocking abilities for everyone's convenience.

By the way, for the ones who still don't know what GPT is, I recommend you to read the next section. I also have a comprehensive yet simple-to-understand article about [how Large Language Models such as GPT work](/gpt-llm-ai-explanation).

## What GPT is

GPT is essentially an advanced computer program (or model) designed to process and generate human-like text by predicting what word should come next in a sentence.

Imagine it as a super-smart autocomplete feature that has read an enormous library of text and can now write on a wide array of topics by stringing together words in a way that sounds human.

Its creators, OpenAI, have trained it with a vast amount of online text, which is why it can compose anything from poetry to technical manuals, but it isn't conscious or truly understanding—it's just really good at spotting and replicating patterns in language.

I wrote more for those who want to go deeper: "[How do language-based AIs, such as GPT, work?](/gpt-llm-ai-explanation)"

## Create an account to get your OpenAI API key

1. [Create an account](https://platform.openai.com/login?launch).

![Creating an account on OpenAI](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/229/conversions/Dt2ElwOQoKtwjEhuw2eu1uGceEDJnF-metaQ2xlYW5TaG90IDIwMjMtMTEtMDYgYXQgMTkuNTQuMjZAMngucG5n--medium.jpg)

3. Confirm your email address.
4. [Log in](https://chat.openai.com/auth/login).
5. Check for your free $5 of credits on [this page](https://platform.openai.com/account/billing/overview). Be careful, once you used them, the API keys you will generate won't work.

![The free $5 of credit given to all new developers.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/228/conversions/V2xA6LlqgeEAd87BpKshqkY19sV9rp-metaQ2xlYW5TaG90IDIwMjMtMTEtMDYgYXQgMTkuNTUuMDdAMngucG5n--medium.jpg)

7. [Generate your first API key](https://platform.openai.com/api-keys). Be careful, it will only be displayed once. Copy and paste it into a password manager so it's stored securely.
8. Start using GPT-4 Turbo's API! (Continue reading to learn how.)

![API key generation on OpenAI](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/227/conversions/yZF7oBp7WI9jbq8gFcNWDWtmQDWWXb-metaQ2xlYW5TaG90IDIwMjMtMTEtMDYgYXQgMjAuMDIuMjhAMngucG5n--medium.jpg)


## GPT models pricing

Once you spent your $5 of free credit, you will have to pay for what you use. Don't worry, invoices will be automatically calculated for you. It's not like taxes, haha!

Model | Input | Output
----- | ----- | ------
GPT-4 Turbo (128K context) | $0.01 | $0.03 
GPT-4 (32K context) | $0.06 | $0.12 
GPT-4 (8K context) | $0.03 | $0.06 
GPT-3.5 Turbo (16K context) | $0.0030 | $0.0060

*1K tokens = ~750 words* ([learn more about tokens](https://openai.com/api/pricing/#faq-token))

In this tutorial, we will use the super cheap, but very capable GPT-3.5 Turbo.

## How to use the OpenAI API PHP wrapper (openai-php/client)

The best way to learn is to build. Let's get started by setting up the PHP package and by performing a basic request.

We will focus on the `gpt-3.5-turbo` model. It's cheap, fast and this is the same model that powers ChatGPT for non-premium users. That being said, feel free to use `gpt-4` if you need GPT to be smarter.

The PHP wrapper is great because it'll fit no matter what your favorite framework or CMS is (Symfony, CodeIgniter, CakePHP, WordPress, Magento, etc.).

### Install openai-php/client

First, create a bare-minimum PHP project:

```bash
# Create a directory.
mkdir openai-test

# Go into the directory.
cd openai-test

# Create an empty file.
touch index.php
```

Next, install the [OpenAI client](https://github.com/openai-php/client):

```bash
composer require openai-php/client
```

Then, open the project in your favorite code editor and copy and paste this PHP code snippet:

```php
<?php

require 'vendor/autoload.php';

$client = OpenAI::client('YOUR_API_KEY');
```

[**You can generate your own API key here.**](https://beta.openai.com/account/api-keys)

### Usage of openai-php/client

Once the instance of the OpenAI PHP client has been created, you can start using it by calling the `chat()` method and have a conversation with GPT:

```php
$data = $client->chat()->create([
    'model' => 'gpt-3.5-turbo',
    'messages' => [[
        'role' => 'user',
        'content' => 'Hello!',
     ]],
]);

// Hello there! How can I assist you today?
echo $data['choices'][0]['message']['content'];
```

As you can see, the API is super easy to use. You just have to pass the model you want to use and the messages you want to send to GPT.

## How to use the OpenAI API Laravel wrapper (openai-php/laravel)

The [OpenAI Laravel wrapper](https://github.com/openai-php/laravel) is a package made to help developers get started even more easily with GPT.

The package supports:
- Listing the available models for your account (gpt-4, gpt-3.5-turbo, etc.).
- Using completions (letting AI complete a piece of text).
- Chatting with a model, just like in ChatGPT.
- Transcribing an audio file (useful for podcasts for instance).
- And so much more! This is an extensive package.

### Install openai-php/laravel

Install the package via Composer:

```bash
composer require openai-php/laravel
```

Note that this package will also install openai-php/client, which it depends on to work. The Laravel wrapper just provides a service that register the client in the container and a Facade to make it easier to use.

### Usage of openai-php/laravel

First, make sure [**you have generated your own API key**](https://beta.openai.com/account/api-keys).

Then, publish the configuration file:

```bash
php artisan vendor:publish --provider="OpenAI\Laravel\ServiceProvider"
```

Finally, add your API key it in your *.env* file:

```
OPENAI_API_KEY=your-api-key
```

The Facade makes it super convenient to get started:

```php
$data = OpenAI::chat()->create([
    'model' => 'gpt-3.5-turbo',
    'messages' => [[
        'role' => 'user',
        'content' => 'Hello!',
    ]],
])

// Hello there! How can I assist you today?
echo $data['choices'][0]['message']['content'];
```

As you can see, there are differences with the vanilla PHP client:
1. We skip creating an OpenAI client instance, since the package already did it and stored the instance in the container.
2. We call the Facade instead of a newly created object.

## How to choose your GPT model

Each model (GPT-4 Turbo, GPT-4, GPT-3.5 Turbo, etc.) have different capabilities. "Turbo" models such as `gpt-4-1106-preview` and `gpt-3.5-turbo-1106` are the smartest and most reliable.

Other models like `gpt-4`, `gpt-4-32k-0613`, and even the ones based on GPT-3 like `davinci` are now legacy and OpenAI keeps them for compatibility purposes.

Your choice should be dictated by the kind of task you want to perform and your budget. OpenAI has pages detailing their GPT [models' capabilities](https://platform.openai.com/docs/models) as well as [their pricing](https://openai.com/pricing).

## Build a powerful spam detection tool in 5 minutes with PHP and GPT

Let's say you have a comments system.

You want to make sure the user isn't spamming you.

This was a hard problem to solve. Luckily, AI can help like it's nothing.

1. Choose the model you want to use. It can be `gpt-3.5-turbo`, `gpt-3.5-turbo-16k`, or `gpt-4`.
2. Create a system prompt that defines the purpose of the model. We tell it it's a spam detection tool and give it some rules to follow.
3. Create a user prompt that contains the potentially spammy comment.

```php
$data = OpenAI::chat()->create([
    'model' => 'gpt-3.5-turbo',
    'messages' => [[
        'role' => 'system',
        'content' => <<<'PROMPT'
You are a spam detection tool. Every prompt you get is a user-generated input from my comments system. Tell me if it should pass validation or not.

The only rules to follow are:
* No self-promotion
* No offensive statements
PROMPT,
    ], [
        'role' => 'user',
        'content' => 'Get rich with Bitcoins, now!',
    ]],
	   
]);

// Not valid. This is self-promotion and potentially a scam.
echo $data['choices'][0]['message']['content'];
```

How incredible is that? Building this spam detection tool required almost zero effort.

But what if we change the system prompt to generate a more exploitable reply with JSON?

Here's how to proceed:

1. Change you system prompt to include the instructions to reply with JSON and the desired structure:

```diff
<<<PROMPT
You are a spam detection tool. Every prompt you get is a user-generated input from my commenting system. Tell me if it should pass validation or not.

The only rules to follow are:
* No self-promotion
* No offensive statements
+
+ Reply with the following JSON:
+
+ {
+    "pass": true,
+    "reason": "foo"
+ }

The "reason" key should thoroughly describe why the input passes validation or not.
PROMPT,
```

2. To make sure GPT always replies with JSON, we will also tell it to use the [JSON mode](https://platform.openai.com/docs/guides/text-generation/json-mode). If you don't, GPT can be unreliable and reply with text instead:

```diff
[
  'model' => 'gpt-3.5-turbo',
+ "response_format": {
+     "type": "json_object"
+ }
]
```

Now, for the same *"Get rich with Bitcoins, now!"* comment, the `gpt-3.5-model` replies with:

```php
// {
//     "pass": false,
//     "reason": "This input should not pass validation because it is promoting a get-rich-quick scheme related to cryptocurrencies, which is often associated with fraudulent activities."
// }
echo $data['choices'][0]['message']['content'];
```

From there, you can experiment and refine this spam detection tool even more. 💪

## Even without a PHP or Laravel wrapper, using the OpenAI API is super easy

While openai-php/client is time saving and extremely useful, some people (like me) might prefer to avoid dependencies. This is how easy it is to send requests to OpenAI's API using Laravel's HTTP client:

```php
namespace App\Actions;

use Illuminate\Support\Facades\Http;

class DoSomething
{
    public function do() : string
	{
		$response = Http::withToken(config('services.openai.api_key'))
			->post('https://api.openai.com/v1/chat/completions', [
				'model' => 'gpt-3.5-turbo',
				'messages' => [
					[
						'role' => 'user',
						'content' => 'I want you to do this thing.'
					],
				],
			])
			->throw()
			->json();

		return $response['choices'][0]['message']['content'];
	}
}
```

Then, you can test this code using the `fake()` method of the HTTP client's Facade. Here's an example written with Pest:

```php
use App\Actions\DoSomething;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('asks GPT to do a thing', function () {
    Http::fake([
        'api.openai.com/v1/chat/completions' => Http::response([
            'choices' => [['message' => ['content' => 'Lorem ipsum dolor sit amet.']]],
        ]),
    ]);

    $result = (new DoSomething)->do();

    $this->assertEquals('Lorem ipsum dolor sit amet.', $result);
  
    Http::assertSent(function (Request $request) {
	    // You can also test that:
	    // - You are using the right model ("gpt-3.5-turbo" for instance).
	    // - You sent the right prompt. Because it might contain dynamic
	    // values or be different depending on some parameters.
	});
});
```

## Conclusion

GPT is the basis for a variety of great products nowadays and only the imagination is the limit. I hope you will create something unique thanks to the power of AI!

Talking about unique products, did you know OpenAI provides an [API endpoint to generate true to life voices](https://benjamincrozat.com/openai-tts-api)?
