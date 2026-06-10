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

    // Assert that the response does not have X-Powered-By
    expect($response->headers->has('X-Powered-By'))->toBeFalse();
});
