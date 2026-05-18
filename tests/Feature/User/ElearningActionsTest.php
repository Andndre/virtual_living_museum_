<?php

namespace Tests\Feature\User;

use App\Models\Ebook;
use App\Models\Era;
use App\Models\Materi;
use App\Models\Posttest;
use App\Models\Pretest;
use App\Models\User;

test('user can submit pretest with valid answers', function () {
    $user = User::factory()->create(['role' => 'user']);
    $era = Era::factory()->create();
    $materi = Materi::factory()->create(['era_id' => $era->era_id]);
    $pretest = Pretest::factory()->create(['materi_id' => $materi->materi_id]);

    $response = $this->actingAs($user)->post("/kunjungi-peninggalan/materi/{$materi->materi_id}/pretest", [
        'answers' => [$pretest->pretest_id => 'A'],
    ]);

    $response->assertSessionHasNoErrors();
});

test('user can submit posttest with valid answers', function () {
    $user = User::factory()->create(['role' => 'user']);
    $era = Era::factory()->create();
    $materi = Materi::factory()->create(['era_id' => $era->era_id]);
    $posttest = Posttest::factory()->create(['materi_id' => $materi->materi_id]);

    $response = $this->actingAs($user)->post("/kunjungi-peninggalan/materi/{$materi->materi_id}/posttest", [
        'answers' => [$posttest->posttest_id => 'A'],
    ]);

    $response->assertSessionHasNoErrors();
});

test('user can mark ebook as read', function () {
    $user = User::factory()->create(['role' => 'user']);
    $era = Era::factory()->create();
    $materi = Materi::factory()->create(['era_id' => $era->era_id]);
    Ebook::factory()->create(['materi_id' => $materi->materi_id]);

    $response = $this->actingAs($user)->post("/kunjungi-peninggalan/ebook/{$materi->materi_id}/read", [
        'materi_id' => $materi->materi_id,
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
});

test('unauthenticated user cannot submit pretest', function () {
    $era = Era::factory()->create();
    $materi = Materi::factory()->create(['era_id' => $era->era_id]);

    $this->post("/kunjungi-peninggalan/materi/{$materi->materi_id}/pretest")
        ->assertRedirect('/login');
});

test('unauthenticated user cannot submit posttest', function () {
    $era = Era::factory()->create();
    $materi = Materi::factory()->create(['era_id' => $era->era_id]);

    $this->post("/kunjungi-peninggalan/materi/{$materi->materi_id}/posttest")
        ->assertRedirect('/login');
});
