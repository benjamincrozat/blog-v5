# Framework news analysis prompt

Use [$post-writing](/Users/benjamin/Sites/blog-v5/.agents/skills/post-writing/SKILL.md), [$file-first-posts](/Users/benjamin/Sites/blog-v5/.agents/skills/file-first-posts/SKILL.md), and [$seo-content](/Users/benjamin/Sites/blog-v5/.agents/skills/seo-content/SKILL.md).

Research the most interesting thing that happened this week in `TOPIC`, where `TOPIC` is Laravel, Tailwind CSS, or Livewire.

Work like an editor, not a fan:

- start with current primary sources such as release notes, changelogs, official docs, official blogs, and official product announcements
- decide whether the strongest piece should be straight news, a reported analysis, or a review with opinion
- if the core claim depends on real behavior, test it in a disposable local project when that is cheap and useful
- when the angle is bigger than one package release, look for official examples that show the broader pattern in the market
- separate reported facts from your opinion, and make the opinion earned rather than performative
- stay skeptical of hype; if the news is weak, say so and choose a narrower or more honest angle

Then produce one publication-ready post in `resources/markdown/posts` that:

- leads with what changed and why it matters now
- explains who should care, who probably should not, and what to do next
- adds only the context needed to understand the update

If there is not enough real news for a strong post, do not pad it. Pick one of these fallback angles instead:

- "This release is real, but narrower than the hype"
- "The developer experience is good, but the practical use case is still limited"
- "The feature matters more because the surrounding ecosystem is changing"

Before you finalize, pressure-test the draft with this question:

`Would a competent developer feel better informed after reading this, or just more exposed to buzzwords?`
