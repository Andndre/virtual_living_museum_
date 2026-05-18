<?php

namespace Tests\Feature\User;

use App\Models\User;

test('maps page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/maps');

    $response->assertStatus(200);
});

test('maps view page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/maps/view');

    $response->assertStatus(200);
});

test('maps peninggalan page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/maps/peninggalan');

    $response->assertStatus(200);
});

test('unauthenticated user is redirected to login on maps', function () {
    $this->get('/maps')->assertRedirect('/login');
});

test('unauthenticated user is redirected to login on maps view', function () {
    $this->get('/maps/view')->assertRedirect('/login');
});

test('unauthenticated user is redirected to login on maps peninggalan', function () {
    $this->get('/maps/peninggalan')->assertRedirect('/login');
});
