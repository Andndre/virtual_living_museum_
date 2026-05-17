<?php

namespace Tests\Feature\User;

use App\Models\LaporanPeninggalan;
use App\Models\User;

test('laporan peninggalan index page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/laporan-peninggalan');

    $response->assertStatus(200);
});

test('laporan peninggalan create page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/laporan-peninggalan/create');

    $response->assertStatus(200);
});

test('laporan penigliaan show page renders successfully', function () {
    $user = User::factory()->create(['role' => 'user']);
    $laporan = LaporanPeninggalan::factory()->create();

    $response = $this->actingAs($user)->get("/laporan-peninggalan/{$laporan->laporan_id}");

    $response->assertStatus(200);
});

test('user can submit laporan peninggalan with valid data', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->post('/laporan-peninggalan', [
        'nama_peninggalan' => 'Test Laporan',
        'alamat' => 'Test alamat',
        'lat' => -7.5,
        'lng' => 110.5,
        'deskripsi' => 'Test deskripsi',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('laporan_peninggalan', ['nama_peninggalan' => 'Test Laporan']);
});

test('unauthenticated user is redirected to login on laporan peninggalan index', function () {
    $this->get('/laporan-peninggalan')->assertRedirect('/login');
});