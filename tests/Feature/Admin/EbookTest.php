<?php

use App\Models\Ebook;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Http\UploadedFile;

describe('Ebook Management', function () {
    describe('GET /admin/ebook/{materi_id}', function () {
        it('displays ebook list for materi', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();

            $response = $this->actingAs($admin)->get(route('admin.ebook', $materi->materi_id));

            $response->assertStatus(200);
            $response->assertViewHas('materi');
            $response->assertViewHas('ebooks');
        });
    });

    describe('GET /admin/ebook/{materi_id}/create', function () {
        it('displays ebook create form', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();

            $response = $this->actingAs($admin)->get(route('admin.ebook.create', $materi->materi_id));

            $response->assertStatus(200);
            $response->assertViewHas('materi');
        });
    });

    describe('POST /admin/ebook/{materi_id}', function () {
        it('creates ebook successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();

            $file = UploadedFile::fake()->create('ebook.pdf', 100, 'application/pdf');

            $response = $this->actingAs($admin)->post(route('admin.ebook.store', $materi->materi_id), [
                'judul' => 'Test Ebook',
                'path_file' => $file,
            ]);

            $response->assertRedirect(route('admin.ebook', $materi->materi_id));
            $response->assertSessionHas('success');
            $this->assertDatabaseHas('ebook', ['judul' => 'Test Ebook']);
        });

        it('validates required fields', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();

            $response = $this->actingAs($admin)->post(route('admin.ebook.store', $materi->materi_id), []);

            $response->assertSessionHasErrors(['judul', 'path_file']);
        });
    });

    describe('GET /admin/ebook/{materi_id}/{ebook_id}/edit', function () {
        it('displays ebook edit form', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();
            $ebook = Ebook::factory()->create(['materi_id' => $materi->materi_id]);

            $response = $this->actingAs($admin)->get(route('admin.ebook.edit', [$materi->materi_id, $ebook->ebook_id]));

            $response->assertStatus(200);
            $response->assertViewHas('ebook');
        });
    });

    describe('PUT /admin/ebook/{materi_id}/{ebook_id}', function () {
        it('updates ebook successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();
            $ebook = Ebook::factory()->create(['materi_id' => $materi->materi_id]);

            $file = UploadedFile::fake()->create('ebook.pdf', 100, 'application/pdf');

            $response = $this->actingAs($admin)->put(route('admin.ebook.update', [$materi->materi_id, $ebook->ebook_id]), [
                'judul' => 'Updated Ebook Title',
                'path_file' => $file,
            ]);

            $response->assertRedirect(route('admin.ebook', $materi->materi_id));
            $this->assertDatabaseHas('ebook', [
                'ebook_id' => $ebook->ebook_id,
                'judul' => 'Updated Ebook Title',
            ]);
        });
    });

    describe('DELETE /admin/ebook/{ebook_id}', function () {
        it('deletes ebook successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $materi = Materi::factory()->create();
            $ebook = Ebook::factory()->create(['materi_id' => $materi->materi_id]);

            $response = $this->actingAs($admin)->delete(route('admin.ebook.destroy', $ebook->ebook_id));

            $response->assertRedirect(route('admin.ebook', $materi->materi_id));
            $response->assertSessionHas('success');
            $this->assertDatabaseMissing('ebook', ['ebook_id' => $ebook->ebook_id]);
        });
    });
});
