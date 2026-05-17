<?php

use App\Models\SitusPeninggalan;
use App\Models\User;
use App\Models\VirtualMuseum;

describe('Virtual Living Museum Management', function () {
  describe('GET /admin/virtual-living-museum', function () {
    it('displays virtual museum list to admin', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      VirtualMuseum::factory()->count(3)->create();

      $response = $this->actingAs($admin)->get(route('admin.virtual-museum'));

      $response->assertStatus(200);
      $response->assertViewIs('admin.virtual-museum.index');
      $response->assertViewHas('virtualMuseums');
    });
  });

  describe('GET /admin/virtual-living-museum/create', function () {
    it('displays virtual museum create form', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      SitusPeninggalan::factory()->count(2)->create();

      $response = $this->actingAs($admin)->get(route('admin.virtual-museum.create'));

      $response->assertStatus(200);
      $response->assertViewIs('admin.virtual-museum.create');
    });
  });

  describe('POST /admin/virtual-living-museum', function () {
    it('creates virtual museum successfully', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $situs = SitusPeninggalan::factory()->create();

      $response = $this->actingAs($admin)->post(route('admin.virtual-museum.store'), [
        'nama' => 'Virtual Museum Test',
        'situs_id' => $situs->situs_id,
        'deskripsi' => 'Test deskripsi',
      ]);

      $response->assertRedirect(route('admin.virtual-museum'));
      $response->assertSessionHas('success');
      $this->assertDatabaseHas('virtual_museum', ['nama' => 'Virtual Museum Test']);
    });

    it('validates required fields', function () {
      $admin = User::factory()->create(['role' => 'admin']);

      $response = $this->actingAs($admin)->post(route('admin.virtual-museum.store'), []);

      $response->assertSessionHasErrors(['nama']);
    });
  });

  describe('GET /admin/virtual-living-museum/{museum_id}', function () {
    it('displays virtual museum details', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $museum = VirtualMuseum::factory()->create();

      $response = $this->actingAs($admin)->get(route('admin.virtual-museum.show', $museum->museum_id));

      $response->assertStatus(200);
      $response->assertViewIs('admin.virtual-museum.show');
      $response->assertViewHas('museum');
    });
  });

  describe('GET /admin/virtual-living-museum/{museum_id}/edit', function () {
    it('displays virtual museum edit form', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $museum = VirtualMuseum::factory()->create();

      $response = $this->actingAs($admin)->get(route('admin.virtual-museum.edit', $museum->museum_id));

      $response->assertStatus(200);
      $response->assertViewIs('admin.virtual-museum.edit');
      $response->assertViewHas('museum');
    });
  });

  describe('PUT /admin/virtual-living-museum/{museum_id}', function () {
    it('updates virtual museum successfully', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $museum = VirtualMuseum::factory()->create();

      $response = $this->actingAs($admin)->put(route('admin.virtual-museum.update', $museum->museum_id), [
        'nama' => 'Updated Museum Name',
        'deskripsi' => 'Updated deskripsi',
      ]);

      $response->assertRedirect(route('admin.virtual-museum'));
      $response->assertSessionHas('success');
      $this->assertDatabaseHas('virtual_museum', [
        'museum_id' => $museum->museum_id,
        'nama' => 'Updated Museum Name',
      ]);
    });
  });

  describe('DELETE /admin/virtual-living-museum/{museum_id}', function () {
    it('deletes virtual museum successfully', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $museum = VirtualMuseum::factory()->create();

      $response = $this->actingAs($admin)->delete(route('admin.virtual-museum.destroy', $museum->museum_id));

      $response->assertRedirect(route('admin.virtual-museum'));
      $response->assertSessionHas('success');
      $this->assertDatabaseMissing('virtual_museum', ['museum_id' => $museum->museum_id]);
    });
  });
});