<?php

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Middleware\Admin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

it('allows admin users through', function () {
    $admin = User::factory()->make([
        'github_login' => 'benjamincrozat',
    ]);

    $request = Request::create('/admin-test', 'GET');
    $request->setUserResolver(fn () => $admin);

    $middleware = new Admin;

    $response = $middleware->handle($request, fn () => new Response('OK'));

    expect($response->getStatusCode())->toBe(Response::HTTP_OK);
    expect($response->getContent())->toBe('OK');
});

it('forbids guests', function () {
    $request = Request::create('/admin-test', 'GET');
    $request->setUserResolver(fn () => null);

    $middleware = new Admin;

    expect(fn () => $middleware->handle($request, fn () => new Response('OK')))
        ->toThrow(HttpException::class);
});

it('forbids authenticated non-admin users', function () {
    $user = User::factory()->make([
        'github_login' => 'someone-else',
    ]);

    $request = Request::create('/admin-test', 'GET');
    $request->setUserResolver(fn () => $user);

    $middleware = new Admin;

    expect(fn () => $middleware->handle($request, fn () => new Response('OK')))
        ->toThrow(HttpException::class);
});
