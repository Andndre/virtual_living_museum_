<?php

use App\Models\KritikSaran;
use App\Models\User;

describe('Feedback Management', function () {
    describe('GET /admin/feedback', function () {
        it('displays feedback list to admin', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $user = User::factory()->create(['role' => 'user']);
            KritikSaran::factory()->count(3)->create(['user_id' => $user->id]);

            $response = $this->actingAs($admin)->get(route('admin.feedback'));

            $response->assertStatus(200);
            $response->assertViewIs('admin.feedback');
            $response->assertViewHas('feedback');
        });
    });

    describe('DELETE /admin/feedback/{id}', function () {
        it('deletes feedback successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $user = User::factory()->create(['role' => 'user']);
            $feedback = KritikSaran::factory()->create(['user_id' => $user->id]);

            $response = $this->actingAs($admin)->delete(route('admin.feedback.destroy', $feedback->ks_id));

            $response->assertRedirect(route('admin.feedback'));
            $response->assertSessionHas('success');
            $this->assertDatabaseMissing('kritik_saran', ['ks_id' => $feedback->ks_id]);
        });
    });
});
