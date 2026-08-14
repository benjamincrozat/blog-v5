# Evidence Standard

Use this standard to decide what must exist before drafting a substantive post. Do not add every evidence type mechanically. Match the proof to the promise.

## Make a Proof Plan

Keep this plan outside the article:

```text
Reader outcome:
Central promise:
Important claims:
- Claim -> evidence method -> artifact or source
Original contribution:
Known gaps or limits:
```

Resolve the plan before polishing prose. If an important claim has no credible evidence path, narrow or remove it.

## Match Proof to the Post

### How-to or API Guide

- Use the smallest complete example a reader can run.
- Record the language, framework, package, model, and platform versions that affect the result.
- Run the commands in a clean or clearly described environment.
- Show the relevant output, response, database state, or test result.
- Include the failure readers are likely to hit and the verified correction when useful.

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

- Capture original screenshots after completing the documented workflow.
- Show the state that proves the step, result, or comparison; do not use decorative logos as evidence.
- Crop distractions while keeping enough context to orient the reader.
- Remove secrets, personal data, tokens, and unrelated account information.
- Give every screenshot useful alt text and nearby explanation.
- Upload through `php artisan app:upload-post-image`; never publish local paths or third-party hotlinks.

### News or Version-Sensitive Post

- Start with the primary announcement, release notes, changelog, documentation, source diff, or issue.
- Attribute claims close to the sentence they support.
- Verify behavioral claims locally when the result is important and practical to reproduce.
- Separate what shipped, what is announced, what is inferred, and what remains unknown.
- Include the exact release, date, and support status when they matter.

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
- Visual claims use an original screenshot when seeing the state materially helps.
- The article contributes something beyond summarizing existing pages.
- Facts, observations, and judgment are distinguishable.
- Failure cases, tradeoffs, or boundaries prevent the advice from sounding universal.
- No filler example, benchmark, image, or section exists only to satisfy a checklist.
