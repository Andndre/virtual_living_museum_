<?php

namespace Tests\Feature\User;

use App\Models\User;

test('home page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/home');

    $response->assertStatus(200);
});

test('unauthenticated user is redirected to login on home', function () {
    $this->get('/home')->assertRedirect('/login');
});

test('statistik page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/statistik');

    $response->assertStatus(200);
});

test('pengembang page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/pengembang');

    $response->assertStatus(200);
});
