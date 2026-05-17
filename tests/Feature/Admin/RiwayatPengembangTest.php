<?php

use App\Models\RiwayatPengembang;
use App\Models\User;

describe('Riwayat Pengembang Management', function () {
    describe('GET /admin/riwayat-pengembang', function () {
        it('displays riwayat pengembang list to admin', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            RiwayatPengembang::factory()->count(3)->create();

            $response = $this->actingAs($admin)->get(route('admin.riwayat-pengembang'));

            $response->assertStatus(200);
            $response->assertViewIs('admin.riwayat-pengembang.index');
            $response->assertViewHas('riwayatPengembang');
        });
    });

    describe('GET /admin/riwayat-pengembang/create', function () {
        it('displays riwayat pengembang create form', function () {
            $admin = User::factory()->create(['role' => 'admin']);

            $response = $this->actingAs($admin)->get(route('admin.riwayat-pengembang.create'));

            $response->assertStatus(200);
            $response->assertViewIs('admin.riwayat-pengembang.create');
        });
    });

    describe('POST /admin/riwayat-pengembang', function () {
        it('creates riwayat pengembang successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);

            $response = $this->actingAs($admin)->post(route('admin.riwayat-pengembang.store'), [
                'judul' => 'Pengembang Test',
                'tahun' => '2024-01-01',
                'tahun_selesai' => '2024-12-31',
            ]);

            $response->assertRedirect(route('admin.riwayat-pengembang'));
            $response->assertSessionHas('success');
            $this->assertDatabaseHas('riwayat_pengembangs', ['judul' => 'Pengembang Test']);
        });

        it('validates required fields', function () {
            $admin = User::factory()->create(['role' => 'admin']);

            $response = $this->actingAs($admin)->post(route('admin.riwayat-pengembang.store'), []);

            $response->assertSessionHasErrors(['judul', 'tahun']);
        });
    });

    describe('GET /admin/riwayat-pengembang/{id}/edit', function () {
        it('displays riwayat pengembang edit form', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $riwayat = RiwayatPengembang::factory()->create();

            $response = $this->actingAs($admin)->get(route('admin.riwayat-pengembang.edit', $riwayat->id));

            $response->assertStatus(200);
            $response->assertViewIs('admin.riwayat-pengembang.edit');
            $response->assertViewHas('riwayatPengembang');
        });
    });

    describe('PUT /admin/riwayat-pengembang/{id}', function () {
        it('updates riwayat pengembang successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $riwayat = RiwayatPengembang::factory()->create();

            $response = $this->actingAs($admin)->put(route('admin.riwayat-pengembang.update', $riwayat->id), [
                'judul' => 'Updated Judul',
                'tahun' => '2025-01-01',
                'tahun_selesai' => '2025-06-30',
            ]);

            $response->assertRedirect(route('admin.riwayat-pengembang'));
            $response->assertSessionHas('success');
            $this->assertDatabaseHas('riwayat_pengembangs', [
                'id' => $riwayat->id,
                'judul' => 'Updated Judul',
            ]);
        });
    });

    describe('DELETE /admin/riwayat-pengembang/{id}', function () {
        it('deletes riwayat pengembang successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $riwayat = RiwayatPengembang::factory()->create();

            $response = $this->actingAs($admin)->delete(route('admin.riwayat-pengembang.destroy', $riwayat->id));

            $response->assertRedirect(route('admin.riwayat-pengembang'));
            $response->assertSessionHas('success');
            $this->assertDatabaseMissing('riwayat_pengembangs', ['id' => $riwayat->id]);
        });
    });
});
