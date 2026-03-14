{{--
Displays the standalone post image preview page.
--}}

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="robots" content="noindex, nofollow, noimageindex" />
        <title>{{ $post->title }} image preview</title>

        <link
            rel="preload"
            as="style"
            href="https://fonts.googleapis.com/css2?family=Outfit:wght@200..800&display=swap"
            onload="this.onload=null;this.rel='stylesheet'"
        />

        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

        <style type="text/tailwindcss">
            @theme {
              --font-sans: Outfit;
            }
          </style>
    </head>
    <body
        class="m-0"
        style="background: {{ $mesh['body'] }};"
    >
        {{-- Renders a fixed 16:9 preview canvas for screenshot generation. --}}
        <main class="grid overflow-hidden place-items-center w-screen h-screen">
            <article
                class="relative w-[1280px] h-[720px] overflow-hidden text-black shadow-[0_30px_80px_rgba(15,23,42,0.12)]"
                style="background: {{ $mesh['canvas'] }};"
            >
                <div
                    class="absolute inset-0"
                    style="background: {{ $mesh['atmosphere'] }};"
                ></div>

                @foreach ($mesh['blobs'] as $blob)
                    <div
                        @class([
                            'absolute rounded-full blur-3xl',
                            $blob['class'],
                        ])
                        style="background: {{ $blob['color'] }};"
                    ></div>
                @endforeach

                <div
                    class="absolute inset-0"
                    style="background: {{ $mesh['veil'] }};"
                ></div>

                <div class="relative flex flex-col justify-between h-full p-16">
                    <header class="max-w-[980px]">
                        <p class="mb-5 font-medium tracking-[0.28em] text-black/45 text-lg uppercase">
                            benjamincrozat.com
                        </p>

                        <h1 class="font-medium tracking-tight text-black/95 text-7xl/[1.15] text-balance">
                            {{ $post->title }}
                        </h1>
                    </header>

                    <footer class="flex items-end">
                        <div class="w-[128px] text-black/75">
                            <x-icon-logo />
                        </div>
                    </footer>
                </div>
            </article>
        </main>
    </body>
</html>
