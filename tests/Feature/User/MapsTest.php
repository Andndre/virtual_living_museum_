<?php

namespace Tests\Feature\User;

use App\Models\Materi;
use App\Models\SitusPeninggalan;
use App\Models\User;
use App\Models\VirtualMuseum;

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

test('all situs are unlocked in demo mode on maps view', function () {
    config(['app.demo_mode' => true]);

    $user = User::factory()->create(['role' => 'user', 'level_sekarang' => 0]);
    $materi = Materi::factory()->create(['urutan' => 5]);
    $situs = SitusPeninggalan::factory()->create(['materi_id' => $materi->materi_id, 'user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/maps/view');

    $response->assertStatus(200);
    $response->assertViewHas('unlockedSitusIds', function ($unlockedIds) use ($situs) {
        return in_array($situs->situs_id, $unlockedIds);
    });
});

test('locked situs obey user level when demo mode is disabled on maps view', function () {
    config(['app.demo_mode' => false]);

    $user = User::factory()->create(['role' => 'user', 'level_sekarang' => 1]);
    $materiLocked = Materi::factory()->create(['urutan' => 5]);
    $materiUnlocked = Materi::factory()->create(['urutan' => 1]);

    $situsLocked = SitusPeninggalan::factory()->create(['materi_id' => $materiLocked->materi_id, 'user_id' => $user->id]);
    $situsUnlocked = SitusPeninggalan::factory()->create(['materi_id' => $materiUnlocked->materi_id, 'user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/maps/view');

    $response->assertStatus(200);
    $response->assertViewHas('unlockedSitusIds', function ($unlockedIds) use ($situsLocked, $situsUnlocked) {
        return ! in_array($situsLocked->situs_id, $unlockedIds) && in_array($situsUnlocked->situs_id, $unlockedIds);
    });
});

test('all situs are marked unlocked on maps peninggalan in demo mode', function () {
    config(['app.demo_mode' => true]);

    $user = User::factory()->create(['role' => 'user', 'level_sekarang' => 0]);
    $materi = Materi::factory()->create(['urutan' => 10]);
    $situs = SitusPeninggalan::factory()->create(['materi_id' => $materi->materi_id, 'user_id' => $user->id]);
    VirtualMuseum::factory()->create(['situs_id' => $situs->situs_id]);

    $response = $this->actingAs($user)->get('/maps/peninggalan');

    $response->assertStatus(200);
    $response->assertViewHas('situs', function ($situsList) use ($situs) {
        $found = $situsList->firstWhere('situs_id', $situs->situs_id);

        return $found && $found->is_unlocked === true;
    });
});

test('situs detail marks is_unlocked true in demo mode regardless of level', function () {
    config(['app.demo_mode' => true]);

    $user = User::factory()->create(['role' => 'user', 'level_sekarang' => 0]);
    $materi = Materi::factory()->create(['urutan' => 10]);
    $situs = SitusPeninggalan::factory()->create(['materi_id' => $materi->materi_id, 'user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('guest.situs.detail', $situs->situs_id));

    $response->assertStatus(200);
    $response->assertViewHas('is_unlocked', true);
});
