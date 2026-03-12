---
id: "01KKEW2798Y97VRPJQ9F4AED64"
title: "Git merge vs rebase: the only mental model you need"
slug: "git-merge-vs-rebase"
author: "benjamincrozat"
description: "Merge and rebase with Git confused me for a long time. If that's also you, give yourself a favor and read this article."
categories:
  - "git"
published_at: 2025-10-01T07:37:00+02:00
modified_at: 2025-11-26T10:27:00+01:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K6FBG7574XYDFT98PCVZV6T3.png"
sponsored_at: null
---
## Introduction

I’ll be honest. The whole git merge vs rebase thing confused me for a long time. All I knew was that rebase looked cleaner, so I did it. If that’s you, this post is for you.

## TL;DR (rebase AND merge)

* **Merge** keeps the true history. Safe, sometimes messy.
* **Rebase** rewrites your local history so it looks linear. Clean, and safe as long as no one else has pulled those commits. Risky on shared history unless the team agrees.
* **Golden rule**: rebase your **own local work**, merge shared work back to `main`.
* My default: rebase locally, then merge into `main` with `--ff-only` or a merge commit depending on team policy.

## What Git is really tracking

Git is a history of snapshots. A **merge** joins two histories and records a merge commit. A **rebase** copies your commits and replays them on top of another base so it looks like you started later. The end state of your files can be identical either way. The difference is how the story reads.

## Git merge, explained as if you’re new to it

Two branches exist. With **merge**, Git glues the two lines together and adds a merge commit that says “this is where they joined.” You keep every fork and join in the timeline. It is honest. It may be busy.

### When I use merge

* Final integration to `main` when multiple people have touched things.
* When I want an explicit join point for a big feature or release.
* When our policy requires a merge commit for traceability.

If you want a linear `main` but still integrate safely, merge with fast-forward only:

```bash
git checkout maingit fetch origingit merge --ff-only feature/add-payments
```

`--ff-only` refuses to create a merge commit if a fast-forward is not possible. It keeps history straight and prevents surprise “merge bubbles.” For full details, see the official [Git merge documentation](https://git-scm.com/docs/git-merge).

## Git rebase, explained in plain words

With **rebase**, Git copies your commits and pastes them on top of the latest `main`. It looks like you started after everyone else and never diverged. That linear look is why people love it.

The danger: rebasing **rewrites commit IDs**. If others already pulled those commits, you just changed the past under their feet. That is how you get “force push” drama. So rebasing public history that other people depend on is risky, and you should only do it when your team agrees and knows how to update their own branches.

### When I use rebase

* While my branch is still private or only on my machine.
* Right before I open or update a PR, so the diff is clean.
* For commit cleanup with `git rebase -i` to squash “fix typo” noise.

A tidy pre-PR refresh:

```bash
git fetch origingit rebase origin/main# resolve conflicts if anygit rebase --continue
```

If your team agrees on it, you can make `git pull` rebase by default:

```bash
git config --global pull.rebase true
```

That tells Git to replay your local commits on top of the fetched branch instead of making a merge commit on every pull.

## Git fast forward vs rebase

When people ask about git fast forward vs rebase, they are really asking how to keep `main` clean while they work on feature branches.

A **fast-forward merge** happens when `main` has not moved since you branched. Git can just move the `main` pointer forward to your feature tip. No new merge commit is made.

With **rebase**, Git makes new commits with new IDs, built on top of the latest `main`. Your branch history is copied so it looks like you started later.

My rule of thumb:

* Rebase your feature branch on top of `main` while you are still working, so your own history stays tidy.
* When the branch is ready and up to date, fast-forward merge it into `main` with `--ff-only`.
* If you need a clear “we shipped this thing here” marker, skip fast-forward and do a `--no-ff` merge on purpose.

This fits my default setup: I rebase local work, then prefer fast-forward merges into `main` so the shared history stays simple.

## A simple decision flow that actually works

1. **Working alone on a feature branch**: Rebase freely while the branch is private and no one else has pulled it. Clean history, minimal noise.
2. **Opened a PR and people are reviewing**: Prefer `git pull --rebase` to stay fresh. Avoid rewriting commits others commented on unless your team is fine with it.
3. **Integrating into `main`**:

   * Option A: Rebase the feature on `main`, then fast-forward merge `--ff-only`. Linear `main`, no merge commit.
   * Option B: Merge with `--no-ff` to keep an explicit merge commit for the feature. Useful for auditing and revert clarity.
4. **Never rebase history that others already based work on without agreement**: If you must, have the team coordinate a forced update and use `--force-with-lease` to reduce collateral damage.

## My opinionated default setup

I like a clean history that does not surprise teammates.

```bash
# Rebase on pull by default
git config --global pull.rebase true

# Refuse accidental non-linear merges when updating maingit config --global pull.ff only

# Make fast-forward only merges the norm when you run git merge (avoid accidental merge commits)
git config --global merge.ff only

# You can also use: git merge --ff-only
```

Why this mix: I rebase my local work to keep it linear, then I integrate with fast-forward where possible. If a feature truly needs a merge commit for context, I use `--no-ff` on purpose.

## Practical workflows you can copy

### Keep a feature branch fresh without noise

```bash
git switch feature/refactor-cachegit fetch origingit rebase origin/main# Fix conflicts if any.git push --force-with-lease  # Only if the branch was already pushed.
```

### Integrate a finished feature with a fast-forward

```bash
git switch maingit fetch origingit merge --ff-only feature/refactor-cachegit push
```

### Prefer a visible merge commit for big features

```bash
git switch maingit merge --no-ff feature/billing-v2git push
```

`--no-ff` forces an explicit merge commit even when fast-forward is possible. Use this when you want a clear “we shipped this feature here” marker.

## Pitfalls and how to avoid them

* **Rebasing commits that others already pulled**: coordinate or expect pain. If you do it, use `git push --force-with-lease` so you do not clobber someone else’s work.
* **Perpetual conflict hell**: if a feature drifts for weeks, rebase more often or slice the work into smaller PRs.
* **Dirty PR diffs**: rebase on `main` right before you push the branch or request review.

## Team policy you can paste in your README

* Rebase freely on private branches that no one else has pulled.
* Do not rewrite public history that others may have pulled without agreement.
* Keep `main` linear with `--ff-only`, except when an explicit merge commit is useful.
* Before merging, rebase on `main` to reduce conflicts.
* Use `--force-with-lease` when rewriting your own pushed branch.

This policy balances readability and safety using what Git actually supports out of the box.

## FAQ: quick answers to common “merge vs rebase” questions

### Does rebase change code differently than merge?
No. If you resolve conflicts the same way, the final snapshot can be identical. The history is what changes.

### Is rebase dangerous?
It is mostly risky when other people already pulled your commits. Rewriting those commits forces everyone else to reconcile. Keep rebases local or coordinate.

### What about interactive rebase for cleanup?
Great for squashing fixups and editing messages before your code is public. It is the standard way to polish a branch.

## Conclusion

You do not need every Git trick to feel confident about git merge vs rebase. Think of it this way: use rebase to rewrite your own local story before anyone else pulls it, and use merge to bring shared work together in a clear way.

My default workflow is simple: I rebase my feature branches often, then fast-forward merge them into `main` with `--ff-only` unless I need a clear merge commit for a big feature. Start with that, adjust for your team, and you will keep history clean without fear.

Once "Git merge vs rebase: the only mental model you need" clicks, these follow-ups make even more sense:

- [25 Laravel best practices, tips, and tricks](/laravel-best-practices)
- [The best web development courses available. Free and paid.](/best-web-development-courses)
- [The best PHP blogs to read in 2025](/best-php-blogs)
- [PHP 8.5: 15 new features and changes](/php-85)

