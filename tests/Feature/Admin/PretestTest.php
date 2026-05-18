<?php

use App\Models\Materi;
use App\Models\Pretest;
use App\Models\User;

describe('Pretest Management', function () {
    describe('GET /admin/materi/{materi_id}/pretest', function () {
        it('displays pretest list for a materi', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();

            $response = $this->actingAs($admin)->get(route('admin.pretest', $materi->materi_id));

            $response->assertStatus(200);
            $response->assertViewHas('materi');
        });
    });

    describe('GET /admin/materi/{materi_id}/pretest/create', function () {
        it('displays pretest create form', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();

            $response = $this->actingAs($admin)->get(route('admin.pretest.create', $materi->materi_id));

            $response->assertStatus(200);
            $response->assertViewHas('materi');
        });
    });

    describe('POST /admin/materi/{materi_id}/pretest', function () {
        it('creates pretest successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();

            $response = $this->actingAs($admin)->post(route('admin.pretest.store', $materi->materi_id), [
                'pertanyaan' => 'Test pretest question?',
                'pilihan_a' => 'Option A',
                'pilihan_b' => 'Option B',
                'pilihan_c' => 'Option C',
                'pilihan_d' => 'Option D',
                'pilihan_e' => 'Option E',
                'jawaban_benar' => 'A',
            ]);

            $response->assertRedirect(route('admin.pretest', $materi->materi_id));
            $response->assertSessionHas('success');
            $this->assertDatabaseHas('pretest', ['pertanyaan' => 'Test pretest question?']);
        });

        it('validates required fields', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();

            $response = $this->actingAs($admin)->post(route('admin.pretest.store', $materi->materi_id), []);

            $response->assertSessionHasErrors(['pertanyaan', 'jawaban_benar']);
        });
    });

    describe('GET /admin/materi/{materi_id}/pretest/{pretest_id}', function () {
        it('displays pretest details', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();
            $pretest = Pretest::factory()->create(['materi_id' => $materi->materi_id]);

            $response = $this->actingAs($admin)->get(route('admin.pretest.show', [$materi->materi_id, $pretest->pretest_id]));

            $response->assertStatus(200);
            $response->assertViewHas('pretest');
        });
    });

    describe('GET /admin/materi/{materi_id}/pretest/{pretest_id}/edit', function () {
        it('displays pretest edit form', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();
            $pretest = Pretest::factory()->create(['materi_id' => $materi->materi_id]);

            $response = $this->actingAs($admin)->get(route('admin.pretest.edit', [$materi->materi_id, $pretest->pretest_id]));

            $response->assertStatus(200);
            $response->assertViewHas('pretest');
        });
    });

    describe('PUT /admin/materi/{materi_id}/pretest/{pretest_id}', function () {
        it('updates pretest successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();
            $pretest = Pretest::factory()->create(['materi_id' => $materi->materi_id]);

            $response = $this->actingAs($admin)->put(route('admin.pretest.update', [$materi->materi_id, $pretest->pretest_id]), [
                'pertanyaan' => 'Updated question?',
                'pilihan_a' => 'New Option A',
                'pilihan_b' => 'Option B',
                'pilihan_c' => 'Option C',
                'pilihan_d' => 'Option D',
                'pilihan_e' => 'Option E',
                'jawaban_benar' => 'B',
            ]);

            $response->assertRedirect(route('admin.pretest', $materi->materi_id));
            $this->assertDatabaseHas('pretest', [
                'pretest_id' => $pretest->pretest_id,
                'pertanyaan' => 'Updated question?',
                'jawaban_benar' => 'B',
            ]);
        });
    });

    describe('DELETE /admin/materi/{materi_id}/pretest/{pretest_id}', function () {
        it('deletes pretest successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();
            $pretest = Pretest::factory()->create(['materi_id' => $materi->materi_id]);

            $response = $this->actingAs($admin)->delete(route('admin.pretest.destroy', [$materi->materi_id, $pretest->pretest_id]));

            $response->assertRedirect(route('admin.pretest', $materi->materi_id));
            $this->assertDatabaseMissing('pretest', ['pretest_id' => $pretest->pretest_id]);
        });
    });
});
