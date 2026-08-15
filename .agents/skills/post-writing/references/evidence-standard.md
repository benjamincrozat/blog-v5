# Evidence Standard

Use this standard to decide what must exist before drafting a substantive post. Do not add every evidence type mechanically. Match the proof to the promise.

## Contents

- Make a proof plan
- Apply the universal evidence rules
- Match proof to the post
- Follow the source rules
- Pass the final evidence gate

## Make a Proof Plan

Keep this plan outside the article:

```text
Reader outcome:
Central promise:
Post shape:
Important claims:
- Claim -> evidence method -> artifact or source
Existing artifacts to inspect:
Original contribution:
Known gaps or limits:
```

Resolve the plan before polishing prose. If an important claim has no credible evidence path, narrow or remove it.

## Universal Evidence Rules

- Test the central behavior when it is technically possible. If it cannot be tested, narrow the claim and do not present the article as a first-hand guide, review, or benchmark.
- Build evidence before prose. Keep the runnable fixture, reproduction, benchmark script, captured output, or visual state available until the final article has been checked.
- Copy code from the tested fixture into the article. Rerun the exact published form after editing so prose and working code cannot drift apart.
- Record the exact versions and assumptions that affect the result. Distinguish officially supported, directly tested, observed failure, and not tested.
- Open every existing screenshot and inspect the real image before deciding whether to keep, replace, crop, or remove it. Never judge a screenshot from its count, filename, URL, dimensions, Markdown, or alt text.
- Inspect visual evidence twice: at readable full size and in the rendered article at its final display width.
- Keep proof close to the claim. Do not hide the only evidence in an unrelated appendix, repository, or screenshot gallery.
- Treat a benchmark, matrix, screenshot, repository, or long example as optional until the claim makes it useful. High standards mean the right proof, not every possible artifact.

## Match Proof to the Post

### How-to or API Guide

- Use the smallest complete example a reader can run.
- Record the language, framework, package, model, and platform versions that affect the result.
- Run the commands in a clean or clearly described environment.
- Show the relevant output, response, database state, or test result.
- Include the failure readers are likely to hit and the verified correction when useful.
- Include imports, setup, configuration, and input that are required for the example to work. Do not call an incomplete excerpt copy-paste ready.
- Test the final code as published, not only an earlier or larger version in the fixture.

### Troubleshooting Guide

- Reproduce the issue or use a real observed failure.
- Preserve the exact error text and the conditions that trigger it.
- Separate symptoms from the root cause.
- Prove the fix with before-and-after output, a focused test, or a changed runtime state.
- State when the fix does not apply.

### Benchmark or Performance Claim

- Ask one narrow question and define the baseline.
- Change one important variable at a time.
- Record hardware, operating system, runtime, framework, dependency versions, configuration, dataset size, and test date.
- Warm up the system when startup cost is not the subject.
- Run enough repetitions to expose variance. Prefer median and a range or percentile over one best run.
- Publish the command, script, or procedure needed to reproduce the result.
- Explain noise, caching, profiler overhead, and limits on generalizing the result.
- Reject a benchmark if the method cannot support the headline claim.

### Comparison or Review

- Perform the same meaningful task with each option.
- Use equivalent versions, inputs, and constraints.
- Distinguish documented capability from behavior personally observed.
- Capture setup friction, failures, output quality, and important tradeoffs.
- Date volatile facts such as pricing, limits, and availability.
- Make a recommendation for a named reader and use case, not a universal winner.

### UI or Workflow Article

- Complete the documented workflow before capturing new screenshots.
- Open and inspect every existing screenshot before planning new ones. Respect manual work by inspecting it directly; do not assume an image is useful or stale until you have viewed it in the current article.
- Check the actual pixels for legibility, relevant context, stale interface details, wrong versions, accidental personal data, secrets, misleading state, and duplicate coverage.
- Show the state that proves the nearby step, result, or comparison. Do not count decorative logos, generated title cards, or screenshots of code as evidence.
- Crop distractions while keeping enough context to orient the reader.
- Add callouts only when the reader would otherwise struggle to find the relevant control or result.
- Remove secrets, personal data, tokens, and unrelated account information.
- Give every screenshot accurate alt text and a nearby explanation of what it proves.
- Inspect the uploaded image and the rendered article before publication. Confirm that text and important details remain readable at the final display width.
- Use `file-first-posts` for image upload and storage rules.

### Reported News

- Start with the primary announcement, release notes, changelog, documentation, source diff, or issue.
- Attribute claims close to the sentence they support.
- Test the central behavioral claim when it is technically possible. Otherwise report what the source says and do not imply first-hand validation.
- Separate what shipped, what is announced, what is inferred, and what remains unknown.
- Include the exact release, date, and support status when they matter.

### New Library, Major Framework Release, or Major Feature

- Confirm the exact release, package, framework, runtime, and stability state. Separate stable, release candidate, beta, preview, announced, and unreleased work.
- Test a clean installation in an isolated environment and record the resolved versions, not only the requested constraints.
- Test the upgrade path when migration is part of the article's promise. Start from a real supported baseline and run the same checks before and after.
- Run at least one central feature example. Show the command, code, result, and focused test or observed state that proves it worked.
- Build a version or support matrix when compatibility differs. Include columns for the combination, official support, whether it was tested here, the result, and important notes. Write `not tested` instead of guessing.
- Compare the new behavior with the previous version when the difference is part of the reader's decision.
- Record setup friction, dependency conflicts, breaking changes, deprecations, changed defaults, failure modes, and a safe fallback or rollback path.
- Link the release notes, changelog, current documentation, upgrade guide, and source change when each adds evidence. Do not rewrite those pages as the article's main value.
- End with a bounded decision for a named reader: adopt now, test first, wait, or skip.
- Add a benchmark only when the article makes a performance claim. Add screenshots only when a visual state proves the feature or workflow.
- Keep a reproducible fixture or script when it materially helps readers verify the result. Do not publish a repository that contains only boilerplate.

### Conceptual Article

- Use a runnable example and a counterexample when code can make the idea concrete.
- Show the decision boundary: when the concept helps, when it adds complexity, and what simpler option exists.
- Do not manufacture a benchmark or screenshot when neither would help the reader.

## Source Rules

- Prefer current primary sources for facts, APIs, versions, limits, pricing, and release behavior.
- Use secondary sources for outside analysis, not as a substitute for available first-party documentation.
- Open and inspect the source; do not cite search-result snippets.
- Keep citations close to the supported claim.
- Never let a source citation imply that a separately inferred conclusion came from that source.

## Final Evidence Gate

A substantive post passes only when:

- The opening delivers the answer or payoff without making the reader hunt.
- Every important factual claim has a current source or direct observed proof.
- Material commands and examples were run, or the unverified gap is disclosed outside the post.
- Quantitative claims include a reproducible method and honest limitations.
- Every existing and new screenshot was opened and inspected. Visual claims use a current, readable screenshot when seeing the state materially helps.
- Version tables distinguish official support from combinations tested for the article.
- Code shown to the reader matches the final tested fixture.
- The article contributes something beyond summarizing existing pages.
- Facts, observations, and judgment are distinguishable.
- Failure cases, tradeoffs, or boundaries prevent the advice from sounding universal.
- No filler example, benchmark, image, or section exists only to satisfy a checklist.
