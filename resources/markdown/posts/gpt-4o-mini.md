---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/user-attachments/assets/80b40164-bace-4d89-9f09-136e708e5e8e
Title: "How to use GPT-4o mini's API for cheap, step by step"
Description: "Get started with GPT-4o mini's API in no time thanks to my handy step-by-step guide."
Published at: 2024-07-19
Categories: ai, gpt
---

## Introduction to GPT-4o mini

GPT-4o mini is, as its name implies, a mini version of the famous GPT-4o Large Language Model made by [OpenAI](https://openai.com). Its capabilities are incredible despite its size and it even manages to be faster and cheaper than GPT-3.5 Turbo while being way more powerful.

GPT-4o mini can generate text outputs known as "completions" that can be used to build a range of applications like truly personal assistants, smart chatbots, grammar checkers, spam filters, code generators, and more! The list could go on forever. It's also the best model for vision!

If you are unfamiliar with LLMs, take some time to get up to speed thanks to my simple-to-understand article about [how LLMs such as GPT work](/gpt-llm-ai-explanation).

Now, let's dive into this step-by-step tutorial that will help you make your first requests to GPT-4o mini!

## Create an account to get your GPT-4o mini API key

1. [Create an account](https://chat.openai.com/auth/login).

![Creating an account on OpenAI](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/229/conversions/Dt2ElwOQoKtwjEhuw2eu1uGceEDJnF-metaQ2xlYW5TaG90IDIwMjMtMTEtMDYgYXQgMTkuNTQuMjZAMngucG5n--medium.jpg)

3. Confirm your email address.
4. [Log in](https://platform.openai.com/login?launch).
5. Check for your free $5 of credits on [this page](https://platform.openai.com/account/billing/overview). Be careful, once you used them, the API keys you will generate won't work until you pay.

![The free $5 of credit given to all new developers.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/228/conversions/V2xA6LlqgeEAd87BpKshqkY19sV9rp-metaQ2xlYW5TaG90IDIwMjMtMTEtMDYgYXQgMTkuNTUuMDdAMngucG5n--medium.jpg)

7. [Generate your first API key for GPT-4o mini](https://platform.openai.com/api-keys). Be careful, it will only be displayed once. Copy and paste it into a password manager so it's stored securely.
8. Start using your API key with GPT-4o mini's API! (Continue reading to learn how.)

![API key generation on OpenAI](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/227/conversions/yZF7oBp7WI9jbq8gFcNWDWtmQDWWXb-metaQ2xlYW5TaG90IDIwMjMtMTEtMDYgYXQgMjAuMDIuMjhAMngucG5n--medium.jpg)

## How to make your first request to GPT-4o mini

Requesting GPT-4o mini's API is easy peazy and we'll do it with curl this time. Obviously, the API can be requested thanks to the HTTP layer of your favorite programming language.

Here's the process broken down into four very clear steps:

1. **Locate your GPT-4o mini API key:** You should have generated this already if you followed the previous section. It usually looks like a long string of random numbers and letters. *Make sure to keep it secure.*

2. **Open your terminal:** If you want to start experimenting with curl, open your terminal.

3. **Input the curl command:** Curl is a command-line tool used to transfer data. For GPT-4o mini's chat API, you could use a command like this:

```bash
curl -X POST \
	-H "Content-Type: application/json" \
	-H "Authorization: Bearer YOUR_API_KEY" \
	https://api.openai.com/v1/chat/completions -d \
	'{
		"model": "gpt-4o-mini",
		"messages": [
			{
				"role": "system",
				"content": "You are an assistant."
			},
			{
				"role": "user",
				"content": "Hello!"
			}
		]
	}'
```
  - Replace `YOUR_API_KEY` with your actual API key.
  - The string after `-d` specifies the request body in JSON format. It gives the model "gpt-4o-mini" (GPT-4o mini) and two messages: a system message that sets up the role of the assistant, and a user message.

4. **Run the command:** After pressing enter, you should see a response from the API in your terminal window after a few seconds at most.

Remember, this is a basic example. You might want to adjust the request depending on your specific needs, such as including more messages in the conversation.

Learn more on [the official API reference for Chat Completions](https://platform.openai.com/docs/api-reference/chat).

**Pro tip**: One API call can accept up to 128,000 tokens with GPT-4o mini (gpt-4o-mini). A token is a numerical representation of your text. All your messages as well as the output from the model cannot exceed this limit. And for those who don't know, 1,000 tokens roughly equals 750 English words.

## How to enable the JSON mode with the GPT-4o mini API

You can now force GPT-4o mini (as well as GPT-4 Turbo and GPT-3.5 Turbo) to output JSON consistently thanks to the new [JSON mode](https://platform.openai.com/docs/guides/text-generation/json-mode).

(Most people here know what JSON is, but for the others, JSON is a way of storing information that both people and computers can understand. It uses text to organize data into lists and sets of "name: value" pairs.)

Before, asking GPT-4 to output JSON was already possible. But you could randomly get text instead of the JSON you requested. The new JSON mode aims to stop that.

Using it is as simple as adding a new object, and setting a system message that instructs the model to reply with JSON (but keep reading, because there are a few gotchas):

```diff
curl -X POST \
	-H "Content-Type: application/json" \
	-H "Authorization: Bearer YOUR_API_KEY" \
	https://api.openai.com/v1/chat/completions -d \
	'{
		"model": "gpt-4o-mini",
		"messages": [
			{
				"role": "system",
-				"content": "You are an assistant."
+				"content": "You are an assistant, and you only reply with JSON."
			},
			{
				"role": "user",
				"content": "Hello!"
			}
-		]
+		],
+		"response_format": {
+			"type": "json_object"
+		}
	}'
```

1. As you can see, we added the following object:

```json
"response_format": {
	"type": "json_object"
}
```

2. We set GPT-4o mini's system message to "You are an assistant, and you only reply with JSON.", but it can be anything you want as long as "JSON" is mentioned. If you don't do that, the API call will fail and throw the error *"'messages' must contain the word 'json' in some form, to use 'responseformat' of type 'jsonobject'."*

```json
{
	"role": "system",
	"content": "You are an assistant, and you only reply with JSON."
}
```

3. But be careful! While the model will now always output JSON, you will never be able to get 100% accuracy in its structure.

## GPT-4o mini's pricing

Pricing for GPT-4o mini can change, so please double check.

That being said, at the time I'm writing these lines, **[GPT-4o mini's pricing](https://openai.com/pricing) is $0.150 per 1 million tokens for the input and $0.600 per 1 million tokens for the output.** Yes, you read it right. **PER MILLION TOKENS.**

This is such good news for developers who want to build their dream tools for cheaper thanks to the best-known language model. I know I already have something planned. What about you?

By the way, here's a table that compares GPT-4o mini's pricing to other models:

|  Model | Input | Output |
|--------|-------|--------|
| **gpt-4o-mini (128K context)** | **$0.15 / 1M tokens** | **$0.60 / 1M tokens** |
| **gpt-4o (128K context)** | **$5 / 1M tokens** | **$15 / 1M tokens** |
| **gpt-3.5-turbo-0125 (16K context)** | **$0.5 / 1M tokens** | **$1.5 / 1M tokens** |

## Ideas to build thanks to GPT-4o mini's API

Artificial Intelligence with GPT-4o mini enables developers to build products we couldn't hope to before. For instance, I created [Nobinge](https://nobinge.watch), a tool that lets you summarize and chat with YouTube videos.

Here are a bunch of ideas to experiment with:
- Additional AI-based features for existing products
- Automated email responses
- Chatbots
- Content summarizers
- Personal assistants
- Personalized teaching programs
- Sentiment analysis tools
- Spam filters

And did you know OpenAI offers developers an API to [add realistic voices to their projects](/openai-tts-api)?
