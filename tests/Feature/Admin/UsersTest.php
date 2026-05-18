<?php

use App\Models\User;

describe('Users Management', function () {
    describe('GET /admin/users', function () {
        it('displays users list to admin', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            User::factory()->count(3)->create(['role' => 'user']);

            $response = $this->actingAs($admin)->get(route('admin.users'));

            $response->assertStatus(200);
            $response->assertViewIs('admin.users.index');
            $response->assertViewHas('users');
        });

        it('redirects non-admin users', function () {
            $user = User::factory()->create(['role' => 'user']);

            $response = $this->actingAs($user)->get(route('admin.users'));

            $response->assertRedirect(route('guest.home'));
        });
    });

    describe('GET /admin/users/{id}', function () {
        it('displays user details to admin', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $targetUser = User::factory()->create(['role' => 'user']);

            $response = $this->actingAs($admin)->get(route('admin.users.show', $targetUser->id));

            $response->assertStatus(200);
            $response->assertViewIs('admin.users.show');
            $response->assertViewHas('user');
        });

        it('returns 404 for non-existent user', function () {
            $admin = User::factory()->create(['role' => 'admin']);

            $response = $this->actingAs($admin)->get(route('admin.users.show', 99999));

            $response->assertStatus(404);
        });
    });

    describe('GET /admin/users/{id}/edit', function () {
        it('displays user edit form to admin', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $targetUser = User::factory()->create(['role' => 'user']);

            $response = $this->actingAs($admin)->get(route('admin.users.edit', $targetUser->id));

            $response->assertStatus(200);
            $response->assertViewIs('admin.users.edit');
            $response->assertViewHas('user');
        });
    });

    describe('PUT /admin/users/{id}', function () {
        it('updates user successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $targetUser = User::factory()->create(['role' => 'user']);

            $response = $this->actingAs($admin)->put(route('admin.users.update', $targetUser->id), [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'role' => 'user',
            ]);

            $response->assertRedirect(route('admin.users'));
            $response->assertSessionHas('success');

            $this->assertDatabaseHas('users', [
                'id' => $targetUser->id,
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
            ]);
        });

        it('validates required fields on update', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $targetUser = User::factory()->create(['role' => 'user']);

            $response = $this->actingAs($admin)->put(route('admin.users.update', $targetUser->id), [
                'name' => '',
                'email' => 'invalid-email',
            ]);

            $response->assertSessionHasErrors(['name', 'email']);
        });

        it('prevents duplicate email on update', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $user1 = User::factory()->create(['role' => 'user', 'email' => 'user1@example.com']);
            $user2 = User::factory()->create(['role' => 'user', 'email' => 'user2@example.com']);

            $response = $this->actingAs($admin)->put(route('admin.users.update', $user2->id), [
                'name' => 'User 2',
                'email' => 'user1@example.com',
                'role' => 'user',
            ]);

            $response->assertSessionHasErrors(['email']);
        });
    });

    describe('DELETE /admin/users/{id}', function () {
        it('deletes user successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $targetUser = User::factory()->create(['role' => 'user']);

            $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $targetUser->id));

            $response->assertRedirect(route('admin.users'));
            $response->assertSessionHas('success');
            $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
        });

        it('prevents admin from deleting themselves', function () {
            $admin = User::factory()->create(['role' => 'admin']);

            $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin->id));

            $response->assertRedirect(route('admin.users'));
            $response->assertSessionHas('error');
            $this->assertDatabaseHas('users', ['id' => $admin->id]);
        });
    });
});
