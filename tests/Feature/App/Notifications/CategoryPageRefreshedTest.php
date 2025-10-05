<?php

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\HtmlString;
use App\Notifications\CategoryPageRefreshed;

it('renders as an email', function () {
    $category = Category::factory()->create();

    $result = new CategoryPageRefreshed($category);

    $rendered = $result
        ->toMail(User::factory()->create())
        ->render();

    expect($rendered)->toBeInstanceOf(HtmlString::class);
});

it('has the expected subject, content, and action', function () {
    $category = Category::factory()->create(['name' => 'Laravel']);

    $message = (new CategoryPageRefreshed($category))->toMail(User::factory()->create());

    expect($message->subject)->toBe('A category page was just refreshed');
    expect($message->introLines)->toHaveCount(1);
    expect($message->introLines[0])->toContain('Laravel');
    expect($message->actionText)->toBe('Check Category Page');
    expect($message->actionUrl)->toBe(route('categories.show', $category));
});

it('sends via the mail channel and is queueable', function () {
    $category = Category::factory()->create();

    $notification = new CategoryPageRefreshed($category);

    expect($notification->via(User::factory()->create()))->toBe(['mail']);
    expect($notification)->toBeInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class);
});
