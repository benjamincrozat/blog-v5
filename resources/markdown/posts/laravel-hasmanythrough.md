---
id: "01KKVHZH5GBSWKDQEMZS9RGQMK"
title: "How to use hasManyThrough in Laravel with one clear example"
slug: "laravel-hasmanythrough"
author: "benjamincrozat"
description: "Learn how Laravel hasManyThrough works with one concrete example, including the relationship definition, custom keys, and when it is a better fit than manual joins."
categories:
  - "laravel"
published_at: 2026-03-16T14:49:40Z
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/laravel-hasmanythrough.png"
sponsored_at: null
---
## Introduction

**`hasManyThrough` lets a Laravel model access many related records through an intermediate model.**

The cleanest example is:

- a project has many environments
- an environment has many deployments
- so a project has many deployments through environments

That is exactly the kind of relationship `hasManyThrough` is for.

If you try to learn this from abstract examples alone, it feels harder than it is. So this guide sticks to one concrete schema from start to finish.

## When `hasManyThrough` is the right relationship

Use `hasManyThrough` when:

- model A has many model B
- model B has many model C
- you want model A to access many model C records directly

That means:

```text
Project -> Environment -> Deployment
```

Here is the relationship visually before we get into the code:

![Diagram showing a project reaching deployments through environments in Laravel](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/laravel-hasmanythrough-project-environment-deployment.png/public)

So from `Project`, you want:

```php
$project->deployments
```

without manually joining tables every time.

## The example schema

Here is the mental model:

```text
projects
- id
- name

environments
- id
- project_id
- name

deployments
- id
- environment_id
- commit_hash
- created_at
```

Relationships:

- one project has many environments
- one environment has many deployments
- one project has many deployments through environments

## Define the base relationships first

Before adding `hasManyThrough`, define the two simpler relationships normally.

### `Project` has many `Environment`

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    public function environments(): HasMany
    {
        return $this->hasMany(Environment::class);
    }
}
```

### `Environment` has many `Deployment`

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Environment extends Model
{
    public function deployments(): HasMany
    {
        return $this->hasMany(Deployment::class);
    }
}
```

## Define `hasManyThrough`

Now add the through relationship on `Project`:

```php
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Project extends Model
{
    public function deployments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Deployment::class,
            Environment::class,
        );
    }
}
```

That is the simplest version.

Laravel can infer the conventional foreign keys here:

- `environments.project_id`
- `deployments.environment_id`

## Query through the relationship

Once the relationship exists, you can query deployments straight from the project:

```php
$project = Project::findOrFail(1);

$deployments = $project->deployments;
```

You can also keep chaining query conditions:

```php
$deployments = $project->deployments()
    ->latest()
    ->limit(10)
    ->get();
```

That is the main benefit of the relationship: you stop rewriting the same join logic by hand.

## What SQL idea is Laravel representing?

Conceptually, Laravel is doing the equivalent of:

```sql
select deployments.*
from deployments
inner join environments on environments.id = deployments.environment_id
where environments.project_id = ?
```

You do not need to write that SQL yourself every time because the relationship already captures it.

## Custom keys in `hasManyThrough`

If your schema does not follow Laravel’s default naming convention, pass the keys explicitly.

The full signature looks like this:

```php
return $this->hasManyThrough(
    Deployment::class,   // final model
    Environment::class,  // intermediate model
    'project_uuid',      // foreign key on environments
    'environment_uuid',  // foreign key on deployments
    'uuid',              // local key on projects
    'uuid',              // local key on environments
);
```

That is the version to use when:

- your foreign keys are non-standard
- you use UUID columns
- you inherited a schema Laravel cannot infer cleanly

For conventional schemas, keep the simpler version. It is easier to read.

## The newer `through()->has()` syntax

Laravel also supports a fluent way to define this relationship when the intermediate relationships already exist.

Example:

```php
return $this->through('environments')->has('deployments');
```

Or using the dynamic syntax:

```php
return $this->throughEnvironments()->hasDeployments();
```

This is nice when the base relationships are already clear and you want the through relationship to read more declaratively.

If your team is not familiar with that syntax yet, the classic `hasManyThrough()` call is still perfectly fine.

## Common pitfalls

### Skipping the intermediate mental model

If the relationship does not make sense as:

```text
A has many B
B has many C
therefore A has many C through B
```

then `hasManyThrough` is probably not the right relationship.

### Using it when you really need many-to-many

If the middle table is a pivot table and both sides can belong to many records, this is not a `hasManyThrough` problem. That is usually a `belongsToMany()` relationship instead.

If that is the actual shape, [this Laravel pivot table guide](/laravel-pivot-table) is the right article.

### Getting custom keys wrong

Once you leave Laravel’s naming conventions, the argument order matters. This is the main place people trip up.

When that happens, go back to the schema and write down:

- foreign key on the intermediate table
- foreign key on the final table
- local key on the parent table
- local key on the intermediate table

Then map each one carefully.

## A practical use case

Here is the relationship in action for a deployment screen:

```php
$deployments = Project::query()
    ->with(['deployments' => fn ($query) => $query->latest()])
    ->findOrFail($projectId)
    ->deployments;
```

That gives you the project’s deployments without loading environments separately in your controller code.

This is the kind of place where `hasManyThrough` improves clarity: the relationship belongs in the model, not scattered across query code.

## When not to use `hasManyThrough`

Do not force it when:

- a plain join is clearer for one-off reporting
- the relationship is actually many-to-many
- the chain between models is more complex than one intermediate model

The goal is maintainable code, not using the fanciest relationship type available.

## Conclusion

`hasManyThrough` is easier than it looks once the example is concrete. If a project has many environments and each environment has many deployments, then a project has many deployments through environments. That is the whole idea.

Start with the base relationships, add the through relationship, and only reach for custom keys when the schema truly needs them.

If you are still tightening model relationships after this, these are the next reads I would keep open:

- [Use pivot tables correctly when the relationship is actually many-to-many](/laravel-pivot-table)
- [Keep subqueries readable when relationships alone are not enough](/laravel-subquery)
- [Get the migration layer right before relationship debugging starts](/laravel-migrations)
