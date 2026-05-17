<?php

namespace Tests\Feature\User;

use App\Models\User;

test('ar marker camera page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/ar-marker/camera');

    $response->assertStatus(200);
});

test('unauthenticated user is redirected to login on ar marker camera', function () {
    $this->get('/ar-marker/camera')->assertRedirect('/login');
});

test('unauthenticated user is redirected to login on ar marker katalog', function () {
    $this->get('/ar-marker/katalog')->assertRedirect('/login');
});