<?php

use App\Models\LaporanPeninggalan;
use App\Models\User;

describe('Reports Management', function () {
  describe('GET /admin/reports', function () {
    it('displays reports list to admin', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $user = User::factory()->create(['role' => 'user']);
      LaporanPeninggalan::factory()->count(3)->create(['user_id' => $user->id]);

      $response = $this->actingAs($admin)->get(route('admin.reports'));

      $response->assertStatus(200);
      $response->assertViewIs('admin.reports');
      $response->assertViewHas('reports');
    });
  });

  describe('GET /admin/reports/{id}', function () {
    it('displays report details', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $user = User::factory()->create(['role' => 'user']);
      $laporan = LaporanPeninggalan::factory()->create(['user_id' => $user->id]);

      $response = $this->actingAs($admin)->get(route('admin.reports.show', $laporan->laporan_id));

      $response->assertStatus(200);
      $response->assertViewIs('admin.reports.show');
      $response->assertViewHas('report');
    });
  });

  describe('DELETE /admin/reports/{id}', function () {
    it('deletes report successfully', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $user = User::factory()->create(['role' => 'user']);
      $laporan = LaporanPeninggalan::factory()->create(['user_id' => $user->id]);

      $response = $this->actingAs($admin)->delete(route('admin.reports.destroy', $laporan->laporan_id));

      $response->assertRedirect(route('admin.reports'));
      $response->assertSessionHas('success');
      $this->assertDatabaseMissing('laporan_peninggalan', ['laporan_id' => $laporan->laporan_id]);
    });
  });
});