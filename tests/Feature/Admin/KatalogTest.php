<?php

use App\Models\Katalog;
use App\Models\User;

describe('Katalog Management', function () {
    describe('GET /admin/katalog', function () {
        it('displays katalog edit page to admin', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            Katalog::factory()->create();

            $response = $this->actingAs($admin)->get(route('admin.katalog.edit'));

            $response->assertStatus(200);
            $response->assertViewIs('admin.katalog.edit');
            $response->assertViewHas('katalog');
        });
    });

    describe('PUT /admin/katalog', function () {
        it('updates katalog successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $katalog = Katalog::factory()->create();

            $response = $this->actingAs($admin)->put(route('admin.katalog.update'), []);

            $response->assertRedirect(route('admin.katalog.edit'));
            $response->assertSessionHas('success');
        });
    });
});
