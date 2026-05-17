<?php

use App\Models\User;

describe('Admin Dashboard', function () {
  it('displays the admin dashboard to authenticated admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.dashboard');
    $response->assertViewHas('stats');
    $response->assertViewHas('recentUsers');
    $response->assertViewHas('recentReports');
    $response->assertViewHas('recentFeedback');
    $response->assertViewHas('monthlyUsers');
  });

  it('redirects regular users away from admin dashboard', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get(route('admin.dashboard'));

    $response->assertRedirect(route('guest.home'));
  });

  it('redirects unauthenticated users to login', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertRedirect(route('login'));
  });

  it('shows correct statistics on dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->count(5)->create(['role' => 'user']);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertStatus(200);
    $response->assertViewHas('stats', function ($stats) {
      return $stats['total_users'] === 5;
    });
  });
});