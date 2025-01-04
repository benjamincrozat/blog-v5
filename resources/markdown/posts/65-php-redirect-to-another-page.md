---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/60/browser_stioxv.jpg
Title: "Effortlessly redirect to another page using PHP"
Description: "Discover how to use PHP code to effortlessly redirect your website's visitors to a different page. Explore the magic of HTTP."
Published at: 2023-09-02
Modified at: 2023-09-19
Categories: php
---

## How to perform a redirection

**To redirect to another page using PHP, run the following code:**

```php
// We make sure no text is echoed before we set the header.

header('Location: https://example.com');

exit;
```

## Understanding the code

1. We make sure no text has been echoed before setting the header. We don't want to pollute our response. Continue reading if you want to know what I'm talking about.
2. Then, using the [`header()`](https://www.php.net/header) function, we set a header that will inform the visitor's browser that its user needs to be redirected to another page. What's a header? I'll explain later.
3. Finally, we stop the execution of the code using [`exit`](https://www.php.net/exit). This isn't mandatory, but this isn't necessary either. It's just common practice.

Basically, using HTTP, the server running your PHP code responds to your visitor's browser and asks it to redirect its user to the URL you provided.

## The anatomy of an HTTP response

Let me show you an example HTTP response, which is how a web server communicates with the browser:

```http
HTTP/1.1 302 Found

Content-Type: text/html
Location: https://example.com

<html>
    <p>This is a redirection.</p>
</html>
```

Weird, right? But this is easy. Here's how a response is constructed:

1. The status line containing:
	- The version of HTTP used by the server.
	- The status code.
	- The reason phrase for the status code.
2. The headers. We can see our `Location` header holding the URL to which we want to redirect the visitor.
3. The body, carrying the HTML of your web page. For a redirect, HTML is usually not needed, though.

Also, do you remember when I talked about not polluting our response? Well, echoing text before using the `header()` in PHP will add whatever text you like in it, which can have side effects.

## 301 or 302? How to choose the kind of redirection with PHP?

The `header()` function in PHP lets you choose which kind of redirect you perform. By default, it creates a `302 Found` redirection:

```php
header('Location: https://example.com');
```

Here's the HTTP response sent on my local machine:

```http
HTTP/1.1 302 Found
Server: nginx/1.25.1
Date: Tue, 19 Sep 2023 18:08:31 GMT
Content-Type: text/html; charset=UTF-8
Transfer-Encoding: chunked
Connection: close
X-Powered-By: PHP/8.2.10
Location: https://example.com
```

To make it a `301 Moved Permanently` kind of redirection, you can leverage the `header()` function's third argument: `$response_code`:

```php
header('Location: https://example.com', response_code: 301);
```

Which sends an HTTP response that looks like this:

```http
HTTP/1.1 301 Moved Permanently
Server: nginx/1.25.1
Date: Tue, 19 Sep 2023 18:13:01 GMT
Content-Type: text/html; charset=UTF-8
Transfer-Encoding: chunked
Connection: close
X-Powered-By: PHP/8.2.10
Location: https://example.com
```
