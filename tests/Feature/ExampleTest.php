<?php

use App\Models\User;

it('returns a successful response', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/home');

    $response->assertStatus(200);
});
