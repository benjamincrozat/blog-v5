# Ranking Reality and SEO Experiments

Use this reference when traffic or rankings matter. Google explains its policies and products, but its public advice is not a complete description of ranking. Build decisions from stronger evidence and this site's own results.

## Contents

- Judge the evidence
- Use the working model
- Apply the tactics that hold up
- Treat grey-hat tactics as experiments
- Keep a test record
- Review the source set

## Judge the Evidence

Use the right source for the question:

1. **This site's Search Console and analytics:** best for deciding what to change on this site.
2. **Controlled SEO tests:** strong evidence that a change caused an outcome on the tested page type, but not a universal rule.
3. **Court exhibits, internal documents, and verified leaks:** strong evidence that a system or field exists. They usually do not reveal its current weight.
4. **Repeated practitioner tests:** useful for forming a hypothesis when the setup and dates are clear.
5. **Single case studies and grey-hat forums:** leads to test, not proof.
6. **Large correlation studies:** useful for finding patterns, but they do not prove cause.
7. **Google public guidance:** useful for policy, eligibility, crawling, and declared product behavior. Do not use it alone to decide what ranks.

For every important recommendation, label the evidence as one of:

- `confirmed mechanism`
- `controlled test`
- `site observation`
- `plausible hypothesis`
- `folklore`

Do not turn one positive case study into a permanent rule. Record the page type, query type, market, date, sample size, and limits.

## Use the Working Model

Current evidence supports a simple model:

- `confirmed mechanism` **Body:** the words, entities, examples, and structure help make the page relevant to the query.
- `confirmed mechanism` **Anchors:** Google uses external and internal links to judge importance and topic. The exact weight is unknown.
- `confirmed mechanism` **User interactions:** Google uses click and search-session data through systems such as NavBoost. The title must earn the right click, and the page must satisfy the same need.
- `confirmed mechanism` **Site authority:** site-level authority signals exist. The exact score and weight are unknown, but a good article on a weak site can still need relevant links, mentions, and branded demand.
- `confirmed mechanism` **Freshness:** Google stores several page dates and has freshness systems. A controlled test also found a gain for fresh listing titles. Apply freshness only when the query calls for it.
- `confirmed mechanism` **Topical focus:** Google's numerical topic models can measure how far a page sits from the site's main topics. The ranking weight is unknown.
- `confirmed mechanism` **Originality:** Google stores originality signals for short content. This rejects a fixed length rule; it does not prove that every short page will rank.
- `confirmed mechanism` **Authorship:** Google stores author names, but current evidence does not show that an author archive page improves rankings. Keep the real byline and author data; do not create author pages only for SEO.

This model is stronger than a checklist of word count, keyword density, title length, FAQ count, or image count.

## Apply the Tactics That Hold Up

### Query and Title Match

- Choose a query group from Search Console and live results, not from a generic keyword tool alone.
- Put the clearest form of the main query near the start of the title when it honestly names the page.
- Make the opening answer and important headings confirm the same topic quickly.
- Use related terms and entities needed to answer the subject fully. Do not repeat close synonyms only to stuff the title or body.
- Do not obey a fixed title character limit. Keep the most useful promise visible before likely truncation because that can affect clicks.

### Internal Authority Flow

- Find the strongest relevant pages that can link to the target page. Prefer pages with traffic, external links, close topical relevance, or a clear reader path.
- Add links in the main content where the anchor and nearby text explain the target. Use descriptive anchors; exact or close-match anchors are allowed when they read naturally.
- Link important pages from more than one useful path. Avoid leaving a priority article dependent on a footer, tag archive, or related-post block.
- Link the target back to its real parent and supporting pages so the topic cluster is clear.
- Do not set one minimum or maximum link count for every page. Check whether the added links improve the route and whether the target receives enough internal authority.

### Click and Search-Session Fit

- Compare the page's query-level impressions, clicks, position, and click-through rate with the titles and result features around it.
- Write a specific title and description that attract the intended searcher, not the most clicks from everyone.
- Match the first screen to the search promise. Give the answer, result, or decision before background.
- Fix slow starts, intrusive elements, unreadable code, weak screenshots, or missing proof that could send the right reader back to search.
- Treat automated click campaigns as a poor default experiment. The leaked systems store both filtered and raw click signals, so fake traffic can be filtered and produces weak learning.

### Real Freshness

- Use a year, month, or updated date when live results and query behavior show that freshness matters.
- Make the update real: retest examples, replace stale screenshots, verify versions, and change outdated advice.
- Keep visible dates, front matter, structured data, and sitemap dates consistent.
- Do not update a date alone and call the page refreshed. If testing a date-only change, label it as a test and keep the baseline.

### Links, Mentions, and Brand Demand

- Assume writing alone may not move a competitive query.
- Give each priority article a reason to be cited: original benchmark, tested compatibility table, useful dataset, tool, failure catalog, visual comparison, or clear first-hand result.
- List realistic pages, newsletters, maintainers, repositories, communities, or clients that could mention or link to the asset. Keep outreach outside the article and do not send anything without approval.
- Track new referring pages, branded queries, direct visits, and qualified leads separately from article quality.

### Topic Ownership

- Prefer articles that strengthen the site's established Laravel, PHP, developer-tooling, and freelance-service authority.
- Create a new page only when it owns a distinct reader job. Merge or narrow pages that compete for the same query.
- Link related pages as a useful cluster, not as a batch of near-duplicate keyword variants.

## Treat Grey-Hat Tactics as Experiments

Grey-hat communities are useful because they test things Google discourages or denies. They also contain sales pitches, weak tests, and many reports of wins without the failures.

- `plausible hypothesis` Paid links, private link networks, expired domains, redirects, and parasite pages placed on someone else's strong domain can move rankings in some markets. They are off-page experiments, not post-writing requirements. Plan them separately with the exact target, cost, expected mechanism, measurement window, and rollback.
- `confirmed mechanism; site-specific effect` Exact-match anchors can make relevance clearer. Vary anchors when the same phrase would look forced or when a link spike would make the test hard to read.
- `folklore` Keyword density targets, hidden text, mass FAQs, fixed word counts, and repetitive title variants have no reliable general target unless this site's data supports one.
- Do not copy a competitor's tactic only because the page ranks. Authority, links, history, and user data may explain the result.
- Do not hide uncertainty. A tactic can work now and still be fragile, short-lived, or impossible to attribute.

## Keep a Test Record

For every meaningful SEO experiment, save this outside the article:

```text
Page or page group:
Query group:
Research date:
Hypothesis and expected mechanism:
Evidence level:
Baseline period and metrics:
One main change:
Other changes that could affect the result:
Start date:
Review date:
Result:
Confidence and limits:
Keep, revise, or roll back:
```

Prefer one clear change when learning matters. For a full editorial refresh, record all changes and do not claim that one item caused the result.

## Review the Source Set

Last reviewed: 2026-08-15. These sources establish the working model. Recheck them when a ranking-sensitive project begins because systems and evidence change.

- [DOJ trial exhibit UPX0004: Body, anchors, and user interactions](https://www.justice.gov/atr/media/1322476/dl?inline=)
- [DOJ trial exhibit UPXD104: NavBoost and user data](https://www.justice.gov/atr/media/1320746/dl?inline=)
- [DOJ trial exhibit PXR0356: ABC signals and topicality](https://www.justice.gov/atr/media/1398871/dl)
- [iPullRank analysis of the Google Content Warehouse leak](https://ipullrank.com/google-algo-leak)
- [SparkToro account and analysis of the leaked documents](https://sparktoro.com/blog/an-anonymous-source-shared-thousands-of-leaked-google-search-api-documents-with-me-everyone-in-seo-should-see-them/)
- [SearchPilot controlled internal-linking test](https://www.searchpilot.com/resources/case-studies/seo-split-test-lessons-increasing-internal-linking)
- [SearchPilot controlled title-keyword test](https://www.searchpilot.com/resources/case-studies/how-does-adding-extra-keywords-to-title-tags-impact-seo)
- [SearchPilot controlled title-freshness test](https://www.searchpilot.com/resources/case-studies/seo-split-test-lessons-adding-month-year-to-title-tags)
- [Ahrefs analysis of links across one million search results](https://ahrefs.com/blog/links-matter-less-but-still-matter/)

Practitioner leads to recheck as hypotheses, not proof:

- [Builder Society internal authority-flow case study](https://www.buildersociety.com/threads/internal-linking-strategy-aka-get-value-out-of-your-linkbait-case-study.3058/)
- [BlackHatWorld internal-linking experiment report](https://www.blackhatworld.com/seo/ama-guide-ive-spent-over-50k-on-internal-linking-experiments-and-tests-this-year-here-is-everything-you-wanted-to-know-about-it.1628963/)
- [BlackHatWorld discussion of the Google ranking-document leak](https://www.blackhatworld.com/seo/breaking-news-google-search-ranking-factors-were-leaked.1602301/)
