<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\VideoPeninggalan;

test('video peninggalan index page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/video-peninggalan');

    $response->assertStatus(200);
});

test('video penigliaan show page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);
    $video = VideoPeninggalan::factory()->create();

    $response = $this->actingAs($user)->get("/video-peninggalan/{$video->id}");

    $response->assertStatus(200);
});

test('unauthenticated user is redirected to login on video peninggalan index', function () {
    $this->get('/video-peninggalan')->assertRedirect('/login');
});

test('unauthenticated user is redirected to login on video peninggalan show', function () {
    $video = VideoPeninggalan::factory()->create();

    $this->get("/video-peninggalan/{$video->id}")->assertRedirect('/login');
});
