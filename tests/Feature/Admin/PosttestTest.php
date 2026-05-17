<?php

use App\Models\Materi;
use App\Models\Posttest;
use App\Models\User;

describe('Posttest Management', function () {
  describe('GET /admin/materi/{materi_id}/posttest', function () {
    it('displays posttest list for a materi', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $materi = Materi::factory()->create();

      $response = $this->actingAs($admin)->get(route('admin.posttest', $materi->materi_id));

      $response->assertStatus(200);
      $response->assertViewHas('materi');
    });
  });

  describe('GET /admin/materi/{materi_id}/posttest/create', function () {
    it('displays posttest create form', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $materi = Materi::factory()->create();

      $response = $this->actingAs($admin)->get(route('admin.posttest.create', $materi->materi_id));

      $response->assertStatus(200);
      $response->assertViewHas('materi');
    });
  });

  describe('POST /admin/materi/{materi_id}/posttest', function () {
    it('creates posttest successfully', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $materi = Materi::factory()->create();

      $response = $this->actingAs($admin)->post(route('admin.posttest.store', $materi->materi_id), [
        'pertanyaan' => 'Test posttest question?',
        'pilihan_a' => 'Option A',
        'pilihan_b' => 'Option B',
        'pilihan_c' => 'Option C',
        'pilihan_d' => 'Option D',
        'pilihan_e' => 'Option E',
        'jawaban_benar' => 'A',
      ]);

      $response->assertRedirect(route('admin.posttest', $materi->materi_id));
      $response->assertSessionHas('success');
      $this->assertDatabaseHas('posttest', ['pertanyaan' => 'Test posttest question?']);
    });

    it('validates required fields', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $materi = Materi::factory()->create();

      $response = $this->actingAs($admin)->post(route('admin.posttest.store', $materi->materi_id), []);

      $response->assertSessionHasErrors(['pertanyaan', 'jawaban_benar']);
    });
  });

  describe('GET /admin/materi/{materi_id}/posttest/{posttest_id}', function () {
    it('displays posttest details', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $materi = Materi::factory()->create();
      $posttest = Posttest::factory()->create(['materi_id' => $materi->materi_id]);

      $response = $this->actingAs($admin)->get(route('admin.posttest.show', [$materi->materi_id, $posttest->posttest_id]));

      $response->assertStatus(200);
      $response->assertViewHas('posttest');
    });
  });

  describe('GET /admin/materi/{materi_id}/posttest/{posttest_id}/edit', function () {
    it('displays posttest edit form', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $materi = Materi::factory()->create();
      $posttest = Posttest::factory()->create(['materi_id' => $materi->materi_id]);

      $response = $this->actingAs($admin)->get(route('admin.posttest.edit', [$materi->materi_id, $posttest->posttest_id]));

      $response->assertStatus(200);
      $response->assertViewHas('posttest');
    });
  });

  describe('PUT /admin/materi/{materi_id}/posttest/{posttest_id}', function () {
    it('updates posttest successfully', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $materi = Materi::factory()->create();
      $posttest = Posttest::factory()->create(['materi_id' => $materi->materi_id]);

      $response = $this->actingAs($admin)->put(route('admin.posttest.update', [$materi->materi_id, $posttest->posttest_id]), [
        'pertanyaan' => 'Updated question?',
        'pilihan_a' => 'New Option A',
        'pilihan_b' => 'Option B',
        'pilihan_c' => 'Option C',
        'pilihan_d' => 'Option D',
        'pilihan_e' => 'Option E',
        'jawaban_benar' => 'B',
      ]);

      $response->assertRedirect(route('admin.posttest', $materi->materi_id));
      $this->assertDatabaseHas('posttest', [
        'posttest_id' => $posttest->posttest_id,
        'pertanyaan' => 'Updated question?',
        'jawaban_benar' => 'B',
      ]);
    });
  });

  describe('DELETE /admin/materi/{materi_id}/posttest/{posttest_id}', function () {
    it('deletes posttest successfully', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $materi = Materi::factory()->create();
      $posttest = Posttest::factory()->create(['materi_id' => $materi->materi_id]);

      $response = $this->actingAs($admin)->delete(route('admin.posttest.destroy', [$materi->materi_id, $posttest->posttest_id]));

      $response->assertRedirect(route('admin.posttest', $materi->materi_id));
      $this->assertDatabaseMissing('posttest', ['posttest_id' => $posttest->posttest_id]);
    });
  });
});