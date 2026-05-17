<?php

use App\Models\Materi;
use App\Models\SitusPeninggalan;
use App\Models\User;

describe('Situs Peninggalan Management', function () {
  describe('GET /admin/situs', function () {
    it('displays situs list to admin', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      SitusPeninggalan::factory()->count(3)->create();

      $response = $this->actingAs($admin)->get(route('admin.situs'));

      $response->assertStatus(200);
      $response->assertViewIs('admin.situs.index');
      $response->assertViewHas('situs');
      $response->assertViewHas('stats');
    });
  });

  describe('GET /admin/situs/create', function () {
    it('displays situs create form', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      Materi::factory()->count(2)->create();

      $response = $this->actingAs($admin)->get(route('admin.situs.create'));

      $response->assertStatus(200);
      $response->assertViewIs('admin.situs.create');
      $response->assertViewHas('materis');
    });
  });

  describe('POST /admin/situs', function () {
    it('creates situs successfully', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $materi = Materi::factory()->create();

      $response = $this->actingAs($admin)->post(route('admin.situs.store'), [
        'nama' => 'Candi Borobudur',
        'alamat' => 'Magelang, Jawa Tengah',
        'lat' => -7.6079,
        'lng' => 110.2038,
        'deskripsi' => 'Candi Buddha terbesar di dunia',
        'materi_id' => $materi->materi_id,
      ]);

      $response->assertRedirect(route('admin.situs'));
      $response->assertSessionHas('success');
      $this->assertDatabaseHas('situs_peninggalan', ['nama' => 'Candi Borobudur']);
    });

    it('validates required fields', function () {
      $admin = User::factory()->create(['role' => 'admin']);

      $response = $this->actingAs($admin)->post(route('admin.situs.store'), []);

      $response->assertSessionHasErrors(['nama', 'alamat', 'lat', 'lng']);
    });

    it('validates lat/lng ranges', function () {
      $admin = User::factory()->create(['role' => 'admin']);

      $response = $this->actingAs($admin)->post(route('admin.situs.store'), [
        'nama' => 'Test',
        'alamat' => 'Test Address',
        'lat' => 100, // Invalid: must be between -90 and 90
        'lng' => 200, // Invalid: must be between -180 and 180
      ]);

      $response->assertSessionHasErrors(['lat', 'lng']);
    });
  });

  describe('GET /admin/situs/{situs_id}', function () {
    it('displays situs details', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $situs = SitusPeninggalan::factory()->create();

      $response = $this->actingAs($admin)->get(route('admin.situs.show', $situs->situs_id));

      $response->assertStatus(200);
      $response->assertViewIs('admin.situs.show');
      $response->assertViewHas('situs');
    });
  });

  describe('GET /admin/situs/{situs_id}/edit', function () {
    it('displays situs edit form', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $situs = SitusPeninggalan::factory()->create();

      $response = $this->actingAs($admin)->get(route('admin.situs.edit', $situs->situs_id));

      $response->assertStatus(200);
      $response->assertViewIs('admin.situs.edit');
      $response->assertViewHas('situs');
    });
  });

  describe('PUT /admin/situs/{situs_id}', function () {
    it('updates situs successfully', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $situs = SitusPeninggalan::factory()->create();

      $response = $this->actingAs($admin)->put(route('admin.situs.update', $situs->situs_id), [
        'nama' => 'Updated Nama Situs',
        'alamat' => 'Updated Alamat',
        'lat' => -7.6079,
        'lng' => 110.2038,
        'deskripsi' => 'Updated deskripsi',
        'materi_id' => $situs->materi_id,
      ]);

      $response->assertRedirect(route('admin.situs.show', $situs->situs_id));
      $response->assertSessionHas('success');
      $this->assertDatabaseHas('situs_peninggalan', [
        'situs_id' => $situs->situs_id,
        'nama' => 'Updated Nama Situs',
      ]);
    });
  });

  describe('DELETE /admin/situs/{situs_id}', function () {
    it('deletes situs successfully', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $situs = SitusPeninggalan::factory()->create();

      $response = $this->actingAs($admin)->delete(route('admin.situs.destroy', $situs->situs_id));

      $response->assertRedirect(route('admin.situs'));
      $response->assertSessionHas('success');
      $this->assertDatabaseMissing('situs_peninggalan', ['situs_id' => $situs->situs_id]);
    });
  });
});