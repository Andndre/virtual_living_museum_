<?php

namespace Tests\Feature\User;

use App\Models\User;

test('pengaturan page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/pengaturan');

    $response->assertStatus(200);
});

test('panduan page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/panduan');

    $response->assertStatus(200);
});

test('marker page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/marker');

    $response->assertStatus(200);
});

test('unauthenticated user is redirected to login on pengaturan', function () {
    $this->get('/pengaturan')->assertRedirect('/login');
});

test('unauthenticated user is redirected to login on panduan', function () {
    $this->get('/panduan')->assertRedirect('/login');
});

test('unauthenticated user is redirected to login on marker', function () {
    $this->get('/marker')->assertRedirect('/login');
});