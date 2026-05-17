<?php

use App\Models\Materi;
use App\Models\Tugas;
use App\Models\User;

describe('Tugas Management', function () {
    describe('GET /admin/materi/{materi_id}/tugas', function () {
        it('displays tugas list for a materi', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();

            $response = $this->actingAs($admin)->get(route('admin.tugas', $materi->materi_id));

            $response->assertStatus(200);
            $response->assertViewHas('materi');
        });
    });

    describe('GET /admin/materi/{materi_id}/tugas/create', function () {
        it('displays tugas create form', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();

            $response = $this->actingAs($admin)->get(route('admin.tugas.create', $materi->materi_id));

            $response->assertStatus(200);
            $response->assertViewHas('materi');
        });
    });

    describe('POST /admin/materi/{materi_id}/tugas', function () {
        it('creates tugas successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();

            $response = $this->actingAs($admin)->post(route('admin.tugas.store', $materi->materi_id), [
                'judul' => 'Test Tugas',
                'deskripsi' => 'Test deskripsi tugas',
                'tipe' => 'file',
            ]);

            $response->assertRedirect(route('admin.tugas', $materi->materi_id));
            $response->assertSessionHas('success');
            $this->assertDatabaseHas('tugas', ['judul' => 'Test Tugas']);
        });

        it('validates required fields', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();

            $response = $this->actingAs($admin)->post(route('admin.tugas.store', $materi->materi_id), []);

            $response->assertSessionHasErrors(['judul', 'deskripsi']);
        });
    });

    describe('GET /admin/materi/{materi_id}/tugas/{tugas_id}/edit', function () {
        it('displays tugas edit form', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();
            $tugas = Tugas::factory()->create(['materi_id' => $materi->materi_id]);

            $response = $this->actingAs($admin)->get(route('admin.tugas.edit', [$materi->materi_id, $tugas->tugas_id]));

            $response->assertStatus(200);
            $response->assertViewHas('tugas');
        });
    });

    describe('PUT /admin/materi/{materi_id}/tugas/{tugas_id}', function () {
        it('updates tugas successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();
            $tugas = Tugas::factory()->create(['materi_id' => $materi->materi_id]);

            $response = $this->actingAs($admin)->put(route('admin.tugas.update', [$materi->materi_id, $tugas->tugas_id]), [
                'judul' => 'Updated Tugas',
                'deskripsi' => 'Updated deskripsi',
                'tipe' => 'teks',
            ]);

            $response->assertRedirect(route('admin.tugas', $materi->materi_id));
            $this->assertDatabaseHas('tugas', [
                'tugas_id' => $tugas->tugas_id,
                'judul' => 'Updated Tugas',
            ]);
        });
    });

    describe('DELETE /admin/materi/{materi_id}/tugas/{tugas_id}', function () {
        it('deletes tugas successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();
            $tugas = Tugas::factory()->create(['materi_id' => $materi->materi_id]);

            $response = $this->actingAs($admin)->delete(route('admin.tugas.destroy', [$materi->materi_id, $tugas->tugas_id]));

            $response->assertRedirect(route('admin.tugas', $materi->materi_id));
            $this->assertDatabaseMissing('tugas', ['tugas_id' => $tugas->tugas_id]);
        });
    });
});
