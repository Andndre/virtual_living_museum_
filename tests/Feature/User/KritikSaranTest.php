<?php

namespace Tests\Feature\User;

use App\Models\User;

test('kritik saran index page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/kritik-saran');

    $response->assertStatus(200);
});

test('user can submit kritik saran with valid data', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->post('/kritik-saran', [
        'pesan' => 'Test feedback message',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('kritik_saran', ['pesan' => 'Test feedback message']);
});

test('user cannot submit empty kritik saran', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->post('/kritik-saran', [
        'pesan' => '',
    ]);

    $response->assertSessionHasErrors('pesan');
});

test('unauthenticated user is redirected to login on kritik saran', function () {
    $this->get('/kritik-saran')->assertRedirect('/login');
});
