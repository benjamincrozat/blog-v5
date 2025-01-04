---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/240/YOxfLGpU0l4Vsy1odZjvDAlVgDPBVx-metaR1BULTMuNSBUdXJiby5wbmc%3D-.png
Title: "How to use GPT-3.5 Turbo's API, step by step"
Description: "Get started with GPT-3.5 Turbo's API in no time thanks to my handy step-by-step guide."
Published at: 2023-11-06
Categories: ai, gpt
---

## Introduction to GPT-3.5 Turbo

GPT-3.5 Turbo is a famous Artificial Intelligence Large Language Model made by [OpenAI](https://openai.com). Its capabilities powered the first version of ChatGPT.

GPT-3.5 Turbo can generate text outputs known as "completions" that can be used to build a range of applications like truly personal assistants, smart chatbots, grammar checkers, spam filters, code generators, and more! The list could go on forever.

If you are unfamiliar with LLMs, take some time to get up to speed thanks to my simple-to-understand article about [how LLMs such as GPT work](/gpt-llm-ai-explanation).

Now, let's dive into this step-by-step tutorial that will help you make your first requests to GPT-3.5 Turbo!

**If you are a paid OpenAI API user, you can enjoy GPT-4 Turbo now and I also wrote a tutorial about it: [Start using GPT-4 Turbo's API in 5 minutes](/gpt-4-turbo).**

## Create an account to get your GPT-3.5 Turbo API key

1. [Create an account](https://chat.openai.com/auth/login).

![Creating an account on OpenAI](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/229/conversions/Dt2ElwOQoKtwjEhuw2eu1uGceEDJnF-metaQ2xlYW5TaG90IDIwMjMtMTEtMDYgYXQgMTkuNTQuMjZAMngucG5n--medium.jpg)

3. Confirm your email address.
4. [Log in](https://platform.openai.com/login?launch).
5. Check for your free $5 of credits on [this page](https://platform.openai.com/account/billing/overview).

![The free $5 of credit given to all new developers.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/228/conversions/V2xA6LlqgeEAd87BpKshqkY19sV9rp-metaQ2xlYW5TaG90IDIwMjMtMTEtMDYgYXQgMTkuNTUuMDdAMngucG5n--medium.jpg)

7. [Generate your first API key](https://platform.openai.com/api-keys). Be careful, it will only be displayed once. Copy and paste it into a password manager so it's stored securely.
8. Start using GPT-3.5 Turbo's API! (Continue reading to learn how.)

![API key generation on OpenAI](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/227/conversions/yZF7oBp7WI9jbq8gFcNWDWtmQDWWXb-metaQ2xlYW5TaG90IDIwMjMtMTEtMDYgYXQgMjAuMDIuMjhAMngucG5n--medium.jpg)

## Make your first request to GPT-3.5 Turbo

Requesting GPT-4 Turbo's API is easy peazy and we'll do it with curl this time. Obviously, the API can be requested thanks to the HTTP layer of your favorite programming language.

Here's the process broken down into four very clear steps:

1. **Locate your API key:** You should have generated this already if you followed the previous section. It usually looks like a long string of random numbers and letters. *Make sure to keep it secure.*

2. **Open your terminal:** If you want to start experimenting with curl, open your terminal.

3. **Input the curl command:** Curl is a command-line tool used to transfer data. For the chat API, you could use a command like this:

```bash
curl -X POST \
	-H "Content-Type: application/json" \
	-H "Authorization: Bearer YOUR_API_KEY" \
	https://api.openai.com/v1/chat/completions -d \
	'{
		"model": "gpt-3.5-turbo-1106",
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
  - The string after `-d` specifies the request body in JSON format. It gives the model "gpt-3.5-turbo-1106" (GPT-3.5 Turbo) two messages: a system message that sets up the role of the assistant, and a user message.

4. **Run the command:** After pressing enter, you should see a response from the API in your terminal window after a few seconds at most.

Remember, this is a basic example. You might want to adjust the request depending on your specific needs, such as including more messages in the conversation.

Learn more on [the official API reference for Chat Completions](https://platform.openai.com/docs/api-reference/chat).

**Pro tip**: One API call can accept up to 128,000 tokens with gpt-4-1106-preview. A token is a numerical representation of your text. All your messages as well as the output from the model cannot exceed this limit. And for those who don't know, 1,000 tokens roughly equals 750 English words.

![An example response of GPT-3.5 Turbo through its API.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/237/conversions/QP7qPDP033gzTsgOlUG5YOJ6QPZb0X-metaQ2xlYW5TaG90IDIwMjMtMTEtMDYgYXQgMjIuMzcuMzZAMngucG5n--medium.jpg)

## Enable the JSON mode with GPT-3.5 Turbo

You can now force GPT-3.5 Turbo (as well as GPT-3.5 Turbo) to output JSON consistently thanks to the new [JSON mode](https://platform.openai.com/docs/guides/text-generation/json-mode).

(Most people here know what JSON is, but for the others, JSON is a way of storing information that both people and computers can understand. It uses text to organize data into lists and sets of "name: value" pairs.)

Before, asking GPT to output JSON was already possible. But you could randomly get text instead of the JSON you requested. The new JSON mode aims to stop that.

Using it is as simple as adding a new object, and setting a system message that instructs the model to reply with JSON (but keep reading, because there are a few gotchas):

```diff
curl -X POST \
	-H "Content-Type: application/json" \
	-H "Authorization: Bearer YOUR_API_KEY" \
	https://api.openai.com/v1/chat/completions -d \
	'{
		"model": "gpt-3.5-turbo-1106",
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

2. We set the system message to "You are an assistant, and you only reply with JSON.", but it can be anything you want as long as "JSON" is mentioned. If you don't do that, the API call will fail and throw the error *"'messages' must contain the word 'json' in some form, to use 'responseformat' of type 'jsonobject'."*

```json
{
	"role": "system",
	"content": "You are an assistant, and you only reply with JSON."
}
```

3. But be careful! While the model will now always output JSON, you will never be able to get 100% accuracy in its structure.

![GPT-3.5 Turbo's JSON mode in action.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/239/conversions/QZRfvuQzN5NYwctfq3FklZsbljiP9j-metaQ2xlYW5TaG90IDIwMjMtMTEtMDYgYXQgMjIuMzguMDhAMngucG5n--medium.jpg)

## GPT-3.5 Turbo's pricing (it's cheaper than ever!)

Pricing can change, so please double check. That being said, at the time I'm writing these lines, **[GPT-3.5 Turbo's pricing](https://openai.com/pricing) is $0.0010 per 1,000 tokens for the input and $0.0020 per 1,000 tokens for the output.**

This is such good news for developers who want to build their dream tools for cheaper thanks to the best-known language model. I know I already have something planned. What about you?

## Ideas to build thanks to GPT-3.5 Turbo's API

Artificial Intelligence enables developers to build products we couldn't hope to before. For instance, I created [Nobinge](https://nobinge.watch), a tool that lets you summarize and chat with YouTube videos.

Here are a bunch of ideas to experiment with:
- Additional AI-based features for existing products
- Automated email responses
- Chatbots
- Content summarizers
- Personal assistants
- Personalized teaching programs
- Sentiment analysis tools
- Spam filters

You can even [add voices to your projects](/openai-tts-api) thanks to another endpoint!
