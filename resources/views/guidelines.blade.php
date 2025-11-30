<x-app
    title="Advertising guidelines"
>
    <article class="container lg:max-w-(--breakpoint-md)">
        <x-prose>
            {!! Markdown::parse(<<< 'MD'
            # Advertising guidelines

            ## Audience fit

            - My audience is mostly web developers from the PHP ecosystem. And for now, it's mostly Laravel.

            ## Quality and originality

            - Be helpful to developers.
            - Show how your product solves a real problem.
            - Teach something. Add a mini tutorial, checklist, or case study.
            - Include real screenshots, terminal output, or code so it doesn’t read like something copied and pasted from ChatGPT.

            ## Lead with free value

            You are probably playing the long game. Help potential customers to know you better by giving one of these:

            - A free tier
            - A coupon or credits
            - A template
            - A GitHub repository
            - A free trial

            ## Structure and length

            - Use headings.
            - Use lists.
            - Use line breaks.
            - Add a TL;DR with key takeaways and a CTA at the beginning.

            ## SEO

            This platform has a DR of ~50. To get the maximum value of your sponsored article, you should write your articles with Google's algorithm in mind and the practices below help achieving that.

            - Do keyword research.
            - Link to other articles on the platform.
            - Cite facts and benchmarks to strengthen your claims.
            - Don't plagiarize or copy content from other articles you sponsored.

            ## Style and tone

            People love expert humans. Use the first person and share real life stories.

            ## AI usage (allowed, but tastefully)

            Not using AI to write nowadays is crazy. But everybody does it and most of the time, it's really obvious. Let's be smarter about it.

            ## Performance and guarantees

            - I can't guarantee rankings or traffic. Results vary by topic and quality.
            MD) !!}
        </x-prose>
    </article>
</x-app>
