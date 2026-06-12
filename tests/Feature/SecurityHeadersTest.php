<?php

use App\Models\User;

it('includes security headers and removes X-Powered-By in the response', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/home');

    $response->assertStatus(200);
    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('Permissions-Policy');
    $response->assertHeader('Content-Security-Policy');

    $csp = $response->headers->get('Content-Security-Policy');
    expect($csp)->not->toContain('http://localhost:5173');

    // Assert that the response does not have X-Powered-By
    expect($response->headers->has('X-Powered-By'))->toBeFalse();
});

it('includes Vite development URLs in CSP when in local environment', function () {
    $user = User::factory()->create(['role' => 'user']);

    // Switch environment to local
    app()->detectEnvironment(fn () => 'local');

    $response = $this->actingAs($user)->get('/home');

    $response->assertStatus(200);
    $csp = $response->headers->get('Content-Security-Policy');
    expect($csp)->toContain('http://localhost:5173');
    expect($csp)->toContain('http://127.0.0.1:5173');
    expect($csp)->toContain('ws://localhost:5173');
});
