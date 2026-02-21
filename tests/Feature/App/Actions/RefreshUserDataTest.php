<?php

use App\Models\User;
use Github\Client;
use Github\Api\User as GithubUserApi;
use Mockery\MockInterface;
use App\Actions\RefreshUserData;
use Illuminate\Support\Facades\Date;
use Github\Exception\RuntimeException;

it('fetches GitHub user data and updates the user model', function () {
    Date::setTestNow(now());

    $data = [
        'login' => 'foo',
        'name' => 'Foo',
        'bio' => 'Lorem ipsum dolor sit amet.',
    ];

    $user = User::factory()->create([
        'github_login' => 'foo',
        'github_data' => ['id' => 123],
    ]);

    $api = mock(GithubUserApi::class, function (MockInterface $mock) use ($user, $data) {
        $mock->shouldReceive('showById')
            ->once()
            ->with($user->github_id)
            ->andReturn($data);
    });

    $client = mock(Client::class, function (MockInterface $mock) use ($api) {
        $mock->shouldReceive('api')
            ->once()
            ->with('user')
            ->andReturn($api);
    });

    app()->instance(Client::class, $client);

    app(RefreshUserData::class)->refresh($user);

    expect($user->refresh()->github_data['user'])->toMatchArray($data);
    expect($user->refresh()->refreshed_at->getTimestamp())->toBe(now()->getTimestamp());
});

it('deletes the user when GitHub returns a Not Found error', function () {
    Date::setTestNow(now());

    $user = User::factory()->create([
        'github_login' => 'foo',
        'github_data' => ['id' => 123],
    ]);

    $api = mock(GithubUserApi::class, function (MockInterface $mock) use ($user) {
        $mock->shouldReceive('showById')
            ->once()
            ->with($user->github_id)
            ->andThrow(new RuntimeException('Not Found'));
    });

    $client = mock(Client::class, function (MockInterface $mock) use ($api) {
        $mock->shouldReceive('api')
            ->once()
            ->with('user')
            ->andReturn($api);
    });

    app()->instance(Client::class, $client);

    app(RefreshUserData::class)->refresh($user);

    expect(User::find($user->id))->toBeNull();
});
