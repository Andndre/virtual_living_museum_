<?php

namespace Tests\Feature\User;

use App\Models\Era;
use App\Models\Materi;
use App\Models\Pretest;
use App\Models\Tugas;
use App\Models\User;

test('materi detail page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);
    $era = Era::factory()->create();
    $materi = Materi::factory()->create(['era_id' => $era->era_id]);

    $response = $this->actingAs($user)->get("/kunjungi-peninggalan/materi/{$materi->materi_id}");

    $response->assertStatus(200);
});

test('pretest page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);
    $era = Era::factory()->create();
    $materi = Materi::factory()->create(['era_id' => $era->era_id]);
    Pretest::factory()->create(['materi_id' => $materi->materi_id]);

    $response = $this->actingAs($user)->get("/kunjungi-peninggalan/materi/{$materi->materi_id}/pretest");

    $response->assertStatus(200);
});

test('tugas page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);
    $era = Era::factory()->create();
    $materi = Materi::factory()->create(['era_id' => $era->era_id]);
    Tugas::factory()->create(['materi_id' => $materi->materi_id]);

    $response = $this->actingAs($user)->get("/kunjungi-peninggalan/materi/{$materi->materi_id}/tugas");

    $response->assertStatus(200);
});

test('unauthenticated user is redirected to login on materi detail', function () {
    $era = Era::factory()->create();
    $materi = Materi::factory()->create(['era_id' => $era->era_id]);

    $this->get("/kunjungi-peninggalan/materi/{$materi->materi_id}")->assertRedirect('/login');
});

test('unauthenticated user is redirected to login on pretest', function () {
    $era = Era::factory()->create();
    $materi = Materi::factory()->create(['era_id' => $era->era_id]);

    $this->get("/kunjungi-peninggalan/materi/{$materi->materi_id}/pretest")->assertRedirect('/login');
});
