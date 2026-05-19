<?php

namespace Tests\Feature\User;

use App\Models\SitusPeninggalan;
use App\Models\User;
use App\Models\VirtualMuseum;
use App\Models\VirtualMuseumObject;

test('can view peninggalan page with situs having museums', function () {
  $user = User::factory()->create(['level_sekarang' => 1, 'progress_level_sekarang' => 1]);
  $situs = SitusPeninggalan::factory()->create(['nama' => 'Situs Bersejarah A', 'user_id' => $user->id]);
  VirtualMuseum::factory()->create(['situs_id' => $situs->situs_id]);

  $response = $this->actingAs($user)->get(route('guest.maps.peninggalan'));

  $response->assertStatus(200);
  $response->assertSee('Situs Bersejarah A');
});

test('filters situs without museums', function () {
  $user = User::factory()->create(['level_sekarang' => 1, 'progress_level_sekarang' => 1]);

  // Create a situs with museum
  $situsWithMuseum = SitusPeninggalan::factory()->create(['nama' => 'Situs Dengan Museum', 'user_id' => $user->id]);
  VirtualMuseum::factory()->create(['situs_id' => $situsWithMuseum->situs_id]);

  // Create a situs without museum
  SitusPeninggalan::factory()->create(['nama' => 'Situs Tanpa Museum', 'user_id' => $user->id]);

  $response = $this->actingAs($user)->get(route('guest.maps.peninggalan'));

  $response->assertStatus(200);
  $response->assertSee('Situs Dengan Museum');
  $response->assertDontSee('Situs Tanpa Museum');
});

test('can search situs on peninggalan page', function () {
  $user = User::factory()->create(['level_sekarang' => 1, 'progress_level_sekarang' => 1]);
  $situs = SitusPeninggalan::factory()->create(['nama' => 'Situs Bersejarah A', 'user_id' => $user->id]);
  VirtualMuseum::factory()->create(['situs_id' => $situs->situs_id]);

  $response = $this->actingAs($user)->get(route('guest.maps.peninggalan', ['q' => 'Bersejarah']));

  $response->assertStatus(200);
  $response->assertSee('Situs Bersejarah A');
});

test('returns no results for unmatched search query', function () {
  $user = User::factory()->create(['level_sekarang' => 1, 'progress_level_sekarang' => 1]);
  $situs = SitusPeninggalan::factory()->create(['nama' => 'Situs A', 'user_id' => $user->id]);
  VirtualMuseum::factory()->create(['situs_id' => $situs->situs_id]);

  $response = $this->actingAs($user)->get(route('guest.maps.peninggalan', ['q' => 'TidakAda']));

  $response->assertStatus(200);
  $response->assertSee('No sites found', false);
});

test('shows heritage objects section in situs detail', function () {
  $user = User::factory()->create(['level_sekarang' => 1, 'progress_level_sekarang' => 1]);
  $situs = SitusPeninggalan::factory()->create(['nama' => 'Situs Test', 'user_id' => $user->id]);
  $museum = VirtualMuseum::factory()->create(['situs_id' => $situs->situs_id]);

  VirtualMuseumObject::factory()->create([
    'situs_id' => $situs->situs_id,
    'museum_id' => $museum->museum_id,
    'nama' => 'Artefak Test',
  ]);

  $response = $this->actingAs($user)->get(route('guest.situs.detail', $situs->situs_id));

  $response->assertStatus(200);
  $response->assertSee('Heritage Objects');
  $response->assertSee('Artefak Test');
});

test('displays object cards in situs detail', function () {
  $user = User::factory()->create(['level_sekarang' => 1, 'progress_level_sekarang' => 1]);
  $situs = SitusPeninggalan::factory()->create(['nama' => 'Situs Test', 'user_id' => $user->id]);
  $museum = VirtualMuseum::factory()->create(['situs_id' => $situs->situs_id]);

  VirtualMuseumObject::factory()->create([
    'situs_id' => $situs->situs_id,
    'museum_id' => $museum->museum_id,
    'nama' => 'Objek Visual Test',
  ]);

  $response = $this->actingAs($user)->get(route('guest.situs.detail', $situs->situs_id));

  $response->assertStatus(200);
  $response->assertSee('Objek Visual Test');
});