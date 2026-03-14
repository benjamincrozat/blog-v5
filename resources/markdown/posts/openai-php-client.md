---
id: "01KKEW27HJEP1C47C28TND87SV"
title: "How to use OpenAI's API in PHP with openai-php/client"
slug: "openai-php-client"
author: "benjamincrozat"
description: "A practical PHP guide to OpenAI with gpt-5-mini, reasoning controls, structured outputs, and tool calling."
categories:
  - "ai"
  - "gpt"
  - "php"
published_at: 2022-10-27T00:00:00+02:00
modified_at: 2026-03-14T11:18:07Z
serp_title: "How to use OpenAI's API in PHP with openai-php/client"
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K29KHRCYXQAS1A3VMRMP7TF1.png"
sponsored_at: null
---
## How to use OpenAI's API in PHP today

If you are starting a PHP integration in 2026, use OpenAI's [Responses API](https://platform.openai.com/docs/api-reference/responses/create) and call it with [`openai-php/client`](https://github.com/openai-php/client).

That package is **community-maintained**, not an official OpenAI SDK. The Laravel adapter, [`openai-php/laravel`](https://github.com/openai-php/laravel), is community-maintained too. OpenAI's current docs position Responses as the modern default, and these PHP packages map to it well.

This page is the practical PHP walkthrough. If you want the broader model-family overview first, open my [GPT-5 API guide](/gpt-5-api).

## Why I would start with gpt-5-mini

For most PHP apps, `gpt-5-mini` is the sweet spot.

It keeps the modern GPT-5 features that matter in real products:

- reasoning controls
- structured outputs
- custom function tools
- built-in tools like web search, file search, code interpreter, and MCP
- streaming

If you need the bigger-picture comparison between GPT-5, mini, and nano, that belongs in [/gpt-5-api](/gpt-5-api). Here, I want to show how to build something useful in PHP without overspending or overcomplicating it.

## Pick the simplest PHP path

You have three sensible options:

- **`openai-php/client`** for plain PHP and framework-agnostic work
- **`openai-php/laravel`** if you are already in Laravel and want a Facade plus easier fakes
- **Laravel's HTTP client** if you want to avoid OpenAI-specific dependencies

If you came here because you searched for `composer require openai-php/client`, yes, that package is still the best place to start.

## Install openai-php/client

The current package requires **PHP 8.2+**.

Install it with Composer:

```bash
composer require openai-php/client
```

If your project does not already have a PSR-18 HTTP client, install one too:

```bash
composer require guzzlehttp/guzzle
```

Then store your key in an environment variable:

```bash
export OPENAI_API_KEY="sk-..."
```

## Make your first request

This is the smallest useful request with the modern API and a reasoning-capable model:

```php
<?php

require 'vendor/autoload.php';

$client = OpenAI::client(getenv('OPENAI_API_KEY'));

$response = $client->responses()->create([
    'model' => 'gpt-5-mini',
    'input' => 'Say hello from PHP in one short sentence.',
    'reasoning' => [
        'effort' => 'minimal',
    ],
    'max_output_tokens' => 60,
]);

echo $response->outputText;
```

That already proves your PHP app is wired correctly.

## Build something useful: a small support agent

Let us use one realistic workflow all the way through this article.

A customer sends this message:

```text
Hi, I was billed twice for my Pro plan today. Please refund the extra charge.
```

We want our PHP app to:

1. classify the issue
2. decide whether a human should step in
3. optionally look up account context with a tool
4. draft a grounded reply

## Return structured output first

Before we introduce tools, start by returning a clean JSON object.

```php
<?php

require 'vendor/autoload.php';

$client = OpenAI::client(getenv('OPENAI_API_KEY'));

$message = <<<'TEXT'
Hi, I was billed twice for my Pro plan today. Please refund the extra charge.
TEXT;

$response = $client->responses()->create([
    'model' => 'gpt-5-mini',
    'instructions' => 'You triage support messages for a SaaS product.',
    'input' => $message,
    'reasoning' => [
        'effort' => 'minimal',
    ],
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
                    'needs_human' => [
                        'type' => 'boolean',
                    ],
                    'suggested_reply' => [
                        'type' => 'string',
                    ],
                ],
                'required' => ['category', 'priority', 'needs_human', 'suggested_reply'],
                'additionalProperties' => false,
            ],
            'strict' => true,
        ],
    ],
    'max_output_tokens' => 220,
]);

$triage = json_decode($response->outputText, true, flags: JSON_THROW_ON_ERROR);

var_dump($triage);
```

That gives you application-friendly output immediately:

```php
[
    'category' => 'billing',
    'priority' => 'high',
    'needs_human' => true,
    'suggested_reply' => 'Sorry about the duplicate charge. I am escalating this to billing now so the extra payment can be reviewed and refunded.',
]
```

This is the moment where OpenAI becomes genuinely useful in PHP apps. You stop parsing prose and start routing clean data.

## Use reasoning effort on purpose

With GPT-5 models, reasoning is something you control.

In the Responses API, that looks like this:

```php
'reasoning' => [
    'effort' => 'minimal',
],
```

This is the quick rule I would use:

- **`minimal`** for classification, extraction, formatting, and other fast app logic
- **`low`** when the model has to weigh a few tradeoffs before answering
- **`medium`** when the task is more ambiguous and you can tolerate more latency

For our support agent, `minimal` is enough for basic triage. If you start involving account context, policy checks, or multiple tools, `low` usually becomes a better default.

## Built-in tools vs custom tools

OpenAI now supports built-in tools such as web search, file search, code interpreter, and MCP. Those are great in the right app.

But for a PHP product, the most important pattern to understand is still **custom function tools**. They let the model ask your application for data it does not already have.

That is what we will do next with a small PHP function called `lookup_subscription`.

## Add one custom tool

Our support agent should not guess whether the user actually has duplicate billing. It should ask the app.

Here is the first request with a custom tool definition:

```php
<?php

require 'vendor/autoload.php';

$client = OpenAI::client(getenv('OPENAI_API_KEY'));

$message = <<<'TEXT'
Hi, I was billed twice for my Pro plan today. Please refund the extra charge.
TEXT;

$response = $client->responses()->create([
    'model' => 'gpt-5-mini',
    'instructions' => <<<'PROMPT'
You are a support agent for a SaaS app.

If the message is about billing, call lookup_subscription before finalizing the answer.
PROMPT,
    'input' => $message,
    'reasoning' => [
        'effort' => 'low',
    ],
    'tools' => [
        [
            'type' => 'function',
            'name' => 'lookup_subscription',
            'description' => 'Look up a customer subscription and recent billing status.',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'email' => [
                        'type' => 'string',
                        'description' => 'Customer email address',
                    ],
                ],
                'required' => ['email'],
                'additionalProperties' => false,
            ],
        ],
    ],
    'max_output_tokens' => 220,
]);
```

At this point the model can decide to call the tool instead of answering immediately.

## Handle the tool call in PHP

When the model wants your tool, `openai-php/client` exposes it in the response output as a `function_call`.

Here is a minimal loop:

```php
<?php

require 'vendor/autoload.php';

use OpenAI\Responses\Responses\Output\OutputFunctionToolCall;

$client = OpenAI::client(getenv('OPENAI_API_KEY'));

function lookup_subscription(string $email): array
{
    return [
        'email' => $email,
        'plan' => 'Pro',
        'billing_status' => 'duplicate_charge_detected',
        'last_payment_amount' => 49,
        'currency' => 'USD',
    ];
}

$message = <<<'TEXT'
Hi, I was billed twice for my Pro plan today. My account email is sam@example.com.
TEXT;

$first = $client->responses()->create([
    'model' => 'gpt-5-mini',
    'instructions' => 'Use lookup_subscription for billing cases before drafting the final answer.',
    'input' => $message,
    'reasoning' => [
        'effort' => 'low',
    ],
    'tools' => [
        [
            'type' => 'function',
            'name' => 'lookup_subscription',
            'description' => 'Look up a customer subscription and recent billing status.',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'email' => [
                        'type' => 'string',
                    ],
                ],
                'required' => ['email'],
                'additionalProperties' => false,
            ],
        ],
    ],
]);

$toolCall = null;

foreach ($first->output as $item) {
    if ($item instanceof OutputFunctionToolCall && $item->name === 'lookup_subscription') {
        $toolCall = $item;
        break;
    }
}

if (! $toolCall) {
    echo $first->outputText;
    exit;
}

$arguments = json_decode($toolCall->arguments, true, flags: JSON_THROW_ON_ERROR);
$toolResult = lookup_subscription($arguments['email']);
```

That gives you the exact function name, arguments, and `call_id` you need for the next step.

## Send the tool result back to the model

This is the part many older PHP tutorials skip.

When you return tool output in the Responses API, send a follow-up request with:

- `previous_response_id`
- an `input` item of type `function_call_output`

```php
$final = $client->responses()->create([
    'model' => 'gpt-5-mini',
    'previous_response_id' => $first->id,
    'input' => [
        [
            'type' => 'function_call_output',
            'call_id' => $toolCall->callId,
            'output' => json_encode($toolResult, JSON_THROW_ON_ERROR),
        ],
    ],
    'text' => [
        'format' => [
            'type' => 'json_schema',
            'name' => 'support_resolution',
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'category' => [
                        'type' => 'string',
                    ],
                    'priority' => [
                        'type' => 'string',
                    ],
                    'needs_human' => [
                        'type' => 'boolean',
                    ],
                    'suggested_reply' => [
                        'type' => 'string',
                    ],
                ],
                'required' => ['category', 'priority', 'needs_human', 'suggested_reply'],
                'additionalProperties' => false,
            ],
            'strict' => true,
        ],
    ],
    'max_output_tokens' => 220,
]);

$resolution = json_decode($final->outputText, true, flags: JSON_THROW_ON_ERROR);

var_dump($resolution);
```

This is the core agent loop in plain PHP:

1. model asks for a tool
2. your app runs the tool
3. your app sends the result back
4. model finishes with grounded output

If you later add more than one tool, keep looping until there are no more function calls left in the response.

## Stream the final answer when the UX matters

If you are building a chat or assistant UI, streaming still works fine with `gpt-5-mini`:

```php
<?php

require 'vendor/autoload.php';

$client = OpenAI::client(getenv('OPENAI_API_KEY'));

$stream = $client->responses()->createStreamed([
    'model' => 'gpt-5-mini',
    'input' => 'Write a calm one-paragraph reply to a user who was billed twice.',
    'reasoning' => [
        'effort' => 'minimal',
    ],
    'max_output_tokens' => 140,
]);

foreach ($stream as $event) {
    if ($event->event === 'response.output_text.delta') {
        echo $event->response->delta;
    }
}
```

I would still keep streaming separate from the tool loop mentally. First get the agent logic right, then add streaming where it improves the UX.

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

Here is the same support-agent flow as a Laravel action:

```php
<?php

namespace App\Actions\Support;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Responses\Output\OutputFunctionToolCall;

class ResolveSupportMessage
{
    public function handle(string $message): array
    {
        $first = OpenAI::responses()->create([
            'model' => 'gpt-5-mini',
            'instructions' => 'Use lookup_subscription for billing cases before drafting the final answer.',
            'input' => $message,
            'reasoning' => [
                'effort' => 'low',
            ],
            'tools' => [
                [
                    'type' => 'function',
                    'name' => 'lookup_subscription',
                    'description' => 'Look up a customer subscription and recent billing status.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'email' => [
                                'type' => 'string',
                            ],
                        ],
                        'required' => ['email'],
                        'additionalProperties' => false,
                    ],
                ],
            ],
        ]);

        $toolCall = collect($first->output)
            ->first(fn ($item) => $item instanceof OutputFunctionToolCall && $item->name === 'lookup_subscription');

        if (! $toolCall instanceof OutputFunctionToolCall) {
            return [
                'suggested_reply' => $first->outputText,
            ];
        }

        $arguments = json_decode($toolCall->arguments, true, flags: JSON_THROW_ON_ERROR);

        $toolResult = [
            'email' => $arguments['email'],
            'plan' => 'Pro',
            'billing_status' => 'duplicate_charge_detected',
        ];

        $final = OpenAI::responses()->create([
            'model' => 'gpt-5-mini',
            'previous_response_id' => $first->id,
            'input' => [
                [
                    'type' => 'function_call_output',
                    'call_id' => $toolCall->callId,
                    'output' => json_encode($toolResult, JSON_THROW_ON_ERROR),
                ],
            ],
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'support_resolution',
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'category' => ['type' => 'string'],
                            'priority' => ['type' => 'string'],
                            'needs_human' => ['type' => 'boolean'],
                            'suggested_reply' => ['type' => 'string'],
                        ],
                        'required' => ['category', 'priority', 'needs_human', 'suggested_reply'],
                        'additionalProperties' => false,
                    ],
                    'strict' => true,
                ],
            ],
        ]);

        return json_decode($final->outputText, true, flags: JSON_THROW_ON_ERROR);
    }
}
```

## Call the same workflow directly with Laravel's HTTP client

If you prefer raw HTTP, keep your API key in `config/services.php`:

```php
'openai' => [
    'key' => env('OPENAI_API_KEY'),
],
```

Then send the first request yourself:

```php
<?php

namespace App\Actions\Support;

use Illuminate\Support\Facades\Http;

class StartSupportResolution
{
    public function handle(string $message): array
    {
        return Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/responses', [
                'model' => 'gpt-5-mini',
                'instructions' => 'Use lookup_subscription for billing cases before drafting the final answer.',
                'input' => $message,
                'reasoning' => [
                    'effort' => 'low',
                ],
                'tools' => [
                    [
                        'type' => 'function',
                        'name' => 'lookup_subscription',
                        'description' => 'Look up a customer subscription and recent billing status.',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'email' => [
                                    'type' => 'string',
                                ],
                            ],
                            'required' => ['email'],
                            'additionalProperties' => false,
                        ],
                    ],
                ],
                'max_output_tokens' => 220,
            ])
            ->throw()
            ->json();
    }
}
```

You would handle the follow-up tool result with the same `previous_response_id` and `function_call_output` pattern shown earlier.

## Test the Laravel version without hitting the API

The Laravel wrapper is still the easiest way to test request-building.

```php
<?php

use App\Actions\Support\ResolveSupportMessage;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Resources\Responses;
use OpenAI\Responses\Responses\CreateResponse;

it('builds a gpt-5-mini support-agent request', function () {
    OpenAI::fake([
        CreateResponse::fake([
            'model' => 'gpt-5-mini',
            'output' => [
                [
                    'type' => 'message',
                    'id' => 'msg_123',
                    'status' => 'completed',
                    'role' => 'assistant',
                    'content' => [
                        [
                            'type' => 'output_text',
                            'text' => '{"suggested_reply":"We are checking the account now."}',
                            'annotations' => [],
                        ],
                    ],
                ],
            ],
            'tools' => [],
            'tool_choice' => 'auto',
            'parallel_tool_calls' => true,
        ]),
    ]);

    app(ResolveSupportMessage::class)->handle(
        'Hi, I was billed twice for my Pro plan today. My account email is sam@example.com.'
    );

    OpenAI::assertSent(Responses::class, function (string $method, array $parameters): bool {
        return $method === 'create'
            && $parameters['model'] === 'gpt-5-mini'
            && ($parameters['reasoning']['effort'] ?? null) === 'low'
            && ($parameters['tools'][0]['name'] ?? null) === 'lookup_subscription';
    });
});
```

That is enough to prove your action is asking for the right model, the right reasoning level, and the right tool.

## Keep the model choice simple

For this kind of PHP workflow, I would start with **`gpt-5-mini`** and only move up when the task clearly needs more reasoning or a bigger budget.

That is also why I do not want this page to become a giant GPT-5 explainer. The job here is to help you ship the PHP integration.

If you want the broader comparison, pricing context, or the GPT-5 family overview, open [my GPT-5 API guide](/gpt-5-api).

## Conclusion

If you want the short version:

- use **Responses API**
- start with **`openai-php/client`**
- default to **`gpt-5-mini`**
- use **structured outputs** for app logic
- use **custom tools** when the model needs data from your own system

If your PHP app is moving from simple prompts toward real agent behavior, these are the next reads I would keep open:

- [Get the broader GPT-5 model overview before you scale usage](/gpt-5-api)
- [Add text-to-speech after your text workflow is solid](/openai-tts-api)
- [Understand how GPT-style models behave before you over-prompt them](/gpt-llm-ai-explanation)
- [Compare a cheaper OpenAI model path too](/gpt-4o-mini)
