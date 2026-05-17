<?php

namespace Tests\Feature\User;

use App\Helper\TokenHelper;
use App\Models\SitusPeninggalan;
use App\Models\User;
use App\Models\VirtualMuseum;

test('situs detail page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);
    $situs = SitusPeninggalan::factory()->create();

    $response = $this->actingAs($user)->get("/situs/{$situs->situs_id}");

    $response->assertStatus(200);
});

test('unauthenticated user is redirected to login on situs detail', function () {
    $situs = SitusPeninggalan::factory()->create();

    $this->get("/situs/{$situs->situs_id}")->assertRedirect('/login');
});