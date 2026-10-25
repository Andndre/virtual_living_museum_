<?php

namespace Tests\Feature\User;

use App\Helper\TokenHelper;
use App\Models\SitusPeninggalan;
use App\Models\User;
use App\Models\VirtualMuseum;

test('authenticated user can view ar museum page with proper fallback elements', function () {
    $user = User::factory()->create(['role' => 'user']);
    $situs = SitusPeninggalan::factory()->create();
    $museum = VirtualMuseum::factory()->create([
        'situs_id' => $situs->situs_id,
        'nama' => 'Spot Utama Pura',
    ]);

    $response = $this->actingAs($user)->get("/situs/{$situs->situs_id}/ar/{$museum->museum_id}");

    $response->assertStatus(200);
    $response->assertSee('Spot Utama Pura');
    $response->assertSee('id="ar-not-supported"', false);
    $response->assertSee('id="qr-code"', false);
    $response->assertSee('id="loading-container"', false);
    $response->assertSee('id="ar-share-url"', false);
    $response->assertSee('id="btn-copy-ar-link"', false);
    $response->assertSee('class="back-button', false);
    $response->assertSee(__('ar.webxr_not_supported_title'));
});

test('unauthenticated user with valid ar token is authenticated and redirected to clean url', function () {
    $user = User::factory()->create(['role' => 'user']);
    $situs = SitusPeninggalan::factory()->create();
    $museum = VirtualMuseum::factory()->create([
        'situs_id' => $situs->situs_id,
    ]);

    $token = TokenHelper::generate($user->id);

    $response = $this->get("/situs/{$situs->situs_id}/ar/{$museum->museum_id}?arToken={$token}");

    $response->assertRedirect("/situs/{$situs->situs_id}/ar/{$museum->museum_id}");
    $this->assertAuthenticatedAs($user);

    $followResponse = $this->actingAs($user)->get("/situs/{$situs->situs_id}/ar/{$museum->museum_id}");
    $followResponse->assertStatus(200);
    $followResponse->assertSee('id="ar-not-supported"', false);
    $followResponse->assertSee('id="qr-code"', false);
});

test('unauthenticated user without token is redirected to login', function () {
    $situs = SitusPeninggalan::factory()->create();
    $museum = VirtualMuseum::factory()->create([
        'situs_id' => $situs->situs_id,
    ]);

    $response = $this->get("/situs/{$situs->situs_id}/ar/{$museum->museum_id}");

    $response->assertRedirect('/login');
});

test('ar museum returns 404 if museum does not belong to situs', function () {
    $user = User::factory()->create(['role' => 'user']);
    $situs1 = SitusPeninggalan::factory()->create();
    $situs2 = SitusPeninggalan::factory()->create();
    $museum = VirtualMuseum::factory()->create([
        'situs_id' => $situs1->situs_id,
    ]);

    $response = $this->actingAs($user)->get("/situs/{$situs2->situs_id}/ar/{$museum->museum_id}");

    $response->assertStatus(404);
});
