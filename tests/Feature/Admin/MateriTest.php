<?php

use App\Models\Era;
use App\Models\Materi;
use App\Models\User;

describe('Materi Management', function () {
  describe('GET /admin/materi', function () {
    it('displays materi list to admin', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $era = Era::factory()->create();
      Materi::factory()->count(3)->create(['era_id' => $era->era_id]);

      $response = $this->actingAs($admin)->get(route('admin.materi'));

      $response->assertStatus(200);
      $response->assertViewIs('admin.materi.index');
      $response->assertViewHas('materis');
    });
  });

  describe('GET /admin/materi/create', function () {
    it('displays materi create form', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      Era::factory()->count(2)->create();

      $response = $this->actingAs($admin)->get(route('admin.materi.create'));

      $response->assertStatus(200);
      $response->assertViewIs('admin.materi.create');
    });
  });

  describe('POST /admin/materi', function () {
    it('creates materi successfully', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $era = Era::factory()->create();

      $response = $this->actingAs($admin)->post(route('admin.materi.store'), [
        'judul' => 'Test Materi',
        'era_id' => $era->era_id,
        'bab' => 1,
        'urutan' => 1,
        'konten' => 'Test konten materi',
      ]);

      $response->assertRedirect(route('admin.materi'));
      $response->assertSessionHas('success');
      $this->assertDatabaseHas('materi', ['judul' => 'Test Materi']);
    });

    it('validates required fields', function () {
      $admin = User::factory()->create(['role' => 'admin']);

      $response = $this->actingAs($admin)->post(route('admin.materi.store'), []);

      $response->assertSessionHasErrors(['judul', 'konten']);
    });
  });

  describe('GET /admin/materi/{id}', function () {
    it('displays materi details', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $materi = Materi::factory()->create();

      $response = $this->actingAs($admin)->get(route('admin.materi.show', $materi->materi_id));

      $response->assertStatus(200);
      $response->assertViewIs('admin.materi.show');
      $response->assertViewHas('materi');
    });
  });

  describe('GET /admin/materi/{id}/edit', function () {
    it('displays materi edit form', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $materi = Materi::factory()->create();

      $response = $this->actingAs($admin)->get(route('admin.materi.edit', $materi->materi_id));

      $response->assertStatus(200);
      $response->assertViewIs('admin.materi.edit');
      $response->assertViewHas('materi');
    });
  });

  describe('PUT /admin/materi/{id}', function () {
    it('updates materi successfully', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $materi = Materi::factory()->create();

      $response = $this->actingAs($admin)->put(route('admin.materi.update', $materi->materi_id), [
        'judul' => 'Updated Judul',
        'konten' => 'Updated konten',
      ]);

      $response->assertRedirect(route('admin.materi'));
      $response->assertSessionHas('success');
      $this->assertDatabaseHas('materi', [
        'id' => $materi->materi_id,
        'judul' => 'Updated Judul',
      ]);
    });
  });

  describe('DELETE /admin/materi/{id}', function () {
    it('deletes materi successfully', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $materi = Materi::factory()->create();

      $response = $this->actingAs($admin)->delete(route('admin.materi.destroy', $materi->materi_id));

      $response->assertRedirect(route('admin.materi'));
      $response->assertSessionHas('success');
      $this->assertDatabaseMissing('materi', ['materi_id' => $materi->materi_id]);
    });
  });

  describe('POST /admin/materi/update-order', function () {
    it('updates materi order successfully', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $materi1 = Materi::factory()->create();
      $materi2 = Materi::factory()->create();

      $response = $this->actingAs($admin)->post(route('admin.materi.update-order'), [
        'order' => [
          ['materi_id' => $materi1->materi_id, 'urutan' => 2],
          ['materi_id' => $materi2->materi_id, 'urutan' => 1],
        ],
      ]);

      $response->assertJson(['success' => true]);
    });
  });
});