<?php

namespace Tests\Feature\User;

use App\Models\Era;
use App\Models\Materi;
use App\Models\User;

test('kunjungi peninggalan index page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/kunjungi-peninggalan');

    $response->assertStatus(200);
});

test('kunjungi peninggalan era page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);
    $era = Era::factory()->create();

    $response = $this->actingAs($user)->get("/kunjungi-peninggalan/era/{$era->era_id}");

    $response->assertStatus(200);
});

test('unauthenticated user is redirected to login on elearning index', function () {
    $this->get('/kunjungi-peninggalan')->assertRedirect('/login');
});

test('unauthenticated user is redirected to login on elearning era', function () {
    $era = Era::factory()->create();

    $this->get("/kunjungi-peninggalan/era/{$era->era_id}")->assertRedirect('/login');
});