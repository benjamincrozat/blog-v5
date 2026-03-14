---
id: "01KKEW27HJEP1C47C28TND87SV"
title: "How to use OpenAI's API in PHP"
slug: "openai-php-client"
author: "benjamincrozat"
description: "A modern PHP guide to OpenAI's Responses API using openai-php/client, Laravel, and direct HTTP requests."
categories:
  - "ai"
  - "gpt"
  - "php"
published_at: 2022-10-27T00:00:00+02:00
modified_at: 2026-03-14T10:48:54Z
serp_title: "How to use OpenAI's API in PHP with openai-php/client"
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K29KHRCYXQAS1A3VMRMP7TF1.png"
sponsored_at: null
---
## How to use OpenAI's API in PHP today

If you want the shortest path in 2026, use OpenAI's [Responses API](https://platform.openai.com/docs/api-reference/responses/create) and call it from PHP with [`openai-php/client`](https://github.com/openai-php/client).

That package is **community-maintained**, not an official OpenAI SDK. The same goes for [`openai-php/laravel`](https://github.com/openai-php/laravel). OpenAI's current docs point new projects toward the Responses API, and these PHP packages map to it cleanly.

If you are still getting comfortable with models and prompts, this quick refresher on [how GPT-style LLMs work](/gpt-llm-ai-explanation) will make the examples below easier to reason about.

## Pick the simplest PHP path

You have three good options:

- **`openai-php/client`**: the best default for plain PHP, Symfony, WordPress, Laravel, or anything else.
- **`openai-php/laravel`**: same client, but with a Facade and nicer test helpers.
- **Laravel's HTTP client**: useful if you want zero OpenAI-specific dependencies.

If you came here specifically for `composer require openai-php/client`, yes, that is still the package I would start with.

## Install openai-php/client

The current package requires **PHP 8.2+**.

Install the client first:

```bash
composer require openai-php/client
```

If your project does not already have a PSR-18 HTTP client, install one too:

```bash
composer require guzzlehttp/guzzle
```

Store your API key in an environment variable before making requests:

```bash
export OPENAI_API_KEY="sk-..."
```

## Make your first request

This is the smallest useful example with the modern Responses API:

```php
<?php

require 'vendor/autoload.php';

$client = OpenAI::client(getenv('OPENAI_API_KEY'));

$response = $client->responses()->create([
    'model' => 'gpt-4o-mini',
    'input' => 'Say hello from PHP in one short sentence.',
    'max_output_tokens' => 60,
]);

echo $response->outputText;
```

That is already enough to prove your PHP app is wired correctly.

## Build something useful: support triage

Now let us turn one incoming support message into something your app can use.

Imagine a customer sends this:

```text
Hi, I was billed twice for my Pro plan today. Please refund the extra charge.
```

You can start with a plain text answer:

```php
<?php

require 'vendor/autoload.php';

$client = OpenAI::client(getenv('OPENAI_API_KEY'));

$message = <<<'TEXT'
Hi, I was billed twice for my Pro plan today. Please refund the extra charge.
TEXT;

$response = $client->responses()->create([
    'model' => 'gpt-4o-mini',
    'instructions' => 'You triage support tickets. Summarize the issue and suggest the next action in two short sentences.',
    'input' => $message,
    'max_output_tokens' => 120,
]);

echo $response->outputText;
```

That works, but structured data is usually more useful than prose.

## Return structured JSON instead

For real applications, I would rather get a compact JSON object back than parse free-form text.

```php
<?php

require 'vendor/autoload.php';

$client = OpenAI::client(getenv('OPENAI_API_KEY'));

$message = <<<'TEXT'
Hi, I was billed twice for my Pro plan today. Please refund the extra charge.
TEXT;

$response = $client->responses()->create([
    'model' => 'gpt-4o-mini',
    'instructions' => 'Classify the support message and draft a short reply.',
    'input' => $message,
    'text' => [
        'format' => [
            'type' => 'json_schema',
            'name' => 'support_triage',
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'category' => [
                        'type' => 'string',
                        'enum' => ['billing', 'bug', 'account', 'feature_request', 'other'],
                    ],
                    'priority' => [
                        'type' => 'string',
                        'enum' => ['low', 'medium', 'high'],
                    ],
                    'suggested_reply' => [
                        'type' => 'string',
                    ],
                ],
                'required' => ['category', 'priority', 'suggested_reply'],
                'additionalProperties' => false,
            ],
            'strict' => true,
        ],
    ],
    'max_output_tokens' => 200,
]);

$triage = json_decode($response->outputText, true, flags: JSON_THROW_ON_ERROR);

var_dump($triage);
```

You now get something your app can route immediately:

```php
[
    'category' => 'billing',
    'priority' => 'high',
    'suggested_reply' => 'Sorry about the duplicate charge. I am escalating this to billing now and we will refund the extra payment.',
]
```

That is much easier to store, validate, or feed into a queue worker.

One small warning: structured outputs are stricter, but you should still keep normal PHP validation and fallback handling around the response.

## Stream the response when you want live output

If you want to show text as it arrives, the package supports streaming too:

```php
<?php

require 'vendor/autoload.php';

$client = OpenAI::client(getenv('OPENAI_API_KEY'));

$stream = $client->responses()->createStreamed([
    'model' => 'gpt-4o-mini',
    'input' => 'Write a calm two-sentence reply to a customer who was billed twice.',
    'max_output_tokens' => 120,
]);

foreach ($stream as $event) {
    if ($event->event === 'response.output_text.delta') {
        echo $event->response->delta;
    }
}
```

I mostly use this for chat UIs, generators, or anything where a blank loading state feels slow.

## Use the Laravel wrapper when you are already on Laravel

The Laravel package installs the same client behind a Facade:

```bash
composer require openai-php/laravel
php artisan openai:install
```

Then add your key to `.env`:

```dotenv
OPENAI_API_KEY=sk-...
```

Now the same support-triage example becomes:

```php
<?php

namespace App\Actions\Support;

use OpenAI\Laravel\Facades\OpenAI;

class TriageSupportMessage
{
    public function handle(string $message): array
    {
        $response = OpenAI::responses()->create([
            'model' => 'gpt-4o-mini',
            'instructions' => 'Classify the support message and draft a short reply.',
            'input' => $message,
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'support_triage',
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'category' => [
                                'type' => 'string',
                                'enum' => ['billing', 'bug', 'account', 'feature_request', 'other'],
                            ],
                            'priority' => [
                                'type' => 'string',
                                'enum' => ['low', 'medium', 'high'],
                            ],
                            'suggested_reply' => [
                                'type' => 'string',
                            ],
                        ],
                        'required' => ['category', 'priority', 'suggested_reply'],
                        'additionalProperties' => false,
                    ],
                    'strict' => true,
                ],
            ],
            'max_output_tokens' => 200,
        ]);

        return json_decode($response->outputText, true, flags: JSON_THROW_ON_ERROR);
    }
}
```

If you already live in Laravel all day, this is usually the cleanest version.

## Call the API directly with Laravel's HTTP client

If you want less package surface area, direct HTTP is still straightforward.

First, map your key in `config/services.php`:

```php
'openai' => [
    'key' => env('OPENAI_API_KEY'),
],
```

Then send the request yourself:

```php
<?php

namespace App\Actions\Support;

use Illuminate\Support\Facades\Http;

class TriageSupportMessage
{
    public function handle(string $message): array
    {
        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/responses', [
                'model' => 'gpt-4o-mini',
                'instructions' => 'Classify the support message and draft a short reply.',
                'input' => $message,
                'text' => [
                    'format' => [
                        'type' => 'json_schema',
                        'name' => 'support_triage',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'category' => [
                                    'type' => 'string',
                                    'enum' => ['billing', 'bug', 'account', 'feature_request', 'other'],
                                ],
                                'priority' => [
                                    'type' => 'string',
                                    'enum' => ['low', 'medium', 'high'],
                                ],
                                'suggested_reply' => [
                                    'type' => 'string',
                                ],
                            ],
                            'required' => ['category', 'priority', 'suggested_reply'],
                            'additionalProperties' => false,
                        ],
                        'strict' => true,
                    ],
                ],
                'max_output_tokens' => 200,
            ])
            ->throw()
            ->json();

        $text = data_get($response, 'output.0.content.0.text');

        if (! is_string($text)) {
            throw new \RuntimeException('OpenAI did not return text output.');
        }

        return json_decode($text, true, flags: JSON_THROW_ON_ERROR);
    }
}
```

This route is perfectly valid if you prefer full control.

## Test it without hitting the API

This is one reason I like the Laravel wrapper. You can fake the response and assert the outgoing request cleanly.

```php
<?php

use App\Actions\Support\TriageSupportMessage;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Resources\Responses;
use OpenAI\Responses\Responses\CreateResponse;

it('triages a support message', function () {
    OpenAI::fake([
        CreateResponse::fake([
            'model' => 'gpt-4o-mini',
            'output' => [
                [
                    'type' => 'message',
                    'id' => 'msg_123',
                    'status' => 'completed',
                    'role' => 'assistant',
                    'content' => [
                        [
                            'type' => 'output_text',
                            'text' => '{"category":"billing","priority":"high","suggested_reply":"Sorry about the duplicate charge. I am escalating this to billing now."}',
                            'annotations' => [],
                        ],
                    ],
                ],
            ],
            'tools' => [],
            'tool_choice' => 'auto',
            'parallel_tool_calls' => true,
            'text' => [
                'format' => [
                    'type' => 'text',
                ],
            ],
        ]),
    ]);

    $result = app(TriageSupportMessage::class)->handle(
        'Hi, I was billed twice for my Pro plan today.'
    );

    expect($result['category'])->toBe('billing');
    expect($result['priority'])->toBe('high');

    OpenAI::assertSent(Responses::class, function (string $method, array $parameters): bool {
        return $method === 'create'
            && $parameters['model'] === 'gpt-4o-mini'
            && isset($parameters['text']['format']);
    });
});
```

That is enough to prove your action builds the right request without burning credits in test runs.

## How to choose a model without overthinking it

For a small classification or extraction task like support triage, I would start with **`gpt-4o-mini`**.

If you need heavier reasoning, bigger context windows, or more ambitious coding help, then compare it with the latest models in OpenAI's [models overview](https://platform.openai.com/docs/models) and [pricing page](https://openai.com/api/pricing/). If you want the broader flagship overview from a PHP angle, my [GPT-5 API guide](/gpt-5-api) is the next stop.

The important part is not the exact model name. It is using the modern Responses API and keeping your prompt plus output shape simple.

## Conclusion

If you want the shortest answer:

- Use **Responses API** for new PHP work.
- Start with **`openai-php/client`** unless you have a strong reason not to.
- Return **structured JSON** as soon as the result needs to drive real app logic.

If this support-triage flow is only your first OpenAI feature, these are the next reads I would keep open:

- [See when GPT-5 is worth the extra cost and complexity](/gpt-5-api)
- [Start with a cheaper OpenAI model before your traffic grows](/gpt-4o-mini)
- [Add text-to-speech once your text workflow is working](/openai-tts-api)
- [Get a plain-English refresher on how GPT-style models behave](/gpt-llm-ai-explanation)
