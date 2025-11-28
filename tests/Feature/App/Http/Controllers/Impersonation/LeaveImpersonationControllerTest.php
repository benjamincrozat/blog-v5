<?php

use App\Models\User;
use Illuminate\Http\Request;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

use Lab404\Impersonate\Services\ImpersonateManager;
use App\Http\Controllers\Impersonation\LeaveImpersonationController;

it('leaves impersonation and redirects to the stored return url', function () {
    $manager = Mockery::mock(ImpersonateManager::class);
    $manager->shouldReceive('isImpersonating')->once()->andReturnTrue();
    $manager->shouldReceive('leave')->once();

    session()->put('impersonate.return', '/admin/users');

    $request = Request::create('/impersonation/leave', 'GET');

    $response = (new LeaveImpersonationController)($request, $manager);

    expect($response->getTargetUrl())->toBe(url('/admin/users'));
});

it('falls back to the referer header when not impersonating', function () {
    $manager = Mockery::mock(ImpersonateManager::class);
    $manager->shouldReceive('isImpersonating')->once()->andReturnFalse();
    $manager->shouldReceive('leave')->never();

    $request = Request::create('/impersonation/leave', 'GET', [], [], [], [
        'HTTP_REFERER' => 'https://example.com/dashboard',
    ]);

    $response = (new LeaveImpersonationController)($request, $manager);

    expect($response->getTargetUrl())->toBe('https://example.com/dashboard');
});

it('redirects to the default route when no hints exist', function () {
    $manager = Mockery::mock(ImpersonateManager::class);
    $manager->shouldReceive('isImpersonating')->once()->andReturnFalse();
    $manager->shouldReceive('leave')->never();

    $request = Request::create('/impersonation/leave', 'GET');

    $response = (new LeaveImpersonationController)($request, $manager);

    expect($response->getTargetUrl())->toBe(route('filament.admin.resources.users.index'));
});

it('integrates with the leave impersonation route', function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    $user = User::factory()->create();

    actingAs($admin);

    $admin->impersonate($user);

    session(['impersonate.return' => '/foo']);

    get(route('leave-impersonation'))
        ->assertRedirect('/foo');

    expect(session()->has(config('laravel-impersonate.session_key')))->toBeFalse();
});

it('redirects from the leave route even when not impersonating', function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);

    session(['impersonate.return' => '/foo']);

    get(route('leave-impersonation'))
        ->assertRedirect('/foo');
});
