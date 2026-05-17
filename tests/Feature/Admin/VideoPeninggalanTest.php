<?php

use App\Models\User;
use App\Models\VideoPeninggalan;
use Illuminate\Http\UploadedFile;

describe('Video Peninggalan Management', function () {
    describe('GET /admin/video-peninggalan', function () {
        it('displays video peninggalan list to admin', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            VideoPeninggalan::factory()->count(3)->create();

            $response = $this->actingAs($admin)->get(route('admin.video-peninggalan.index'));

            $response->assertStatus(200);
            $response->assertViewIs('admin.video-peninggalan.index');
            $response->assertViewHas('data');
        });
    });

    describe('GET /admin/video-peninggalan/create', function () {
        it('displays video peninggalan create form', function () {
            $admin = User::factory()->create(['role' => 'admin']);

            $response = $this->actingAs($admin)->get(route('admin.video-peninggalan.create'));

            $response->assertStatus(200);
            $response->assertViewIs('admin.video-peninggalan.create');
        });
    });

    describe('POST /admin/video-peninggalan', function () {
        it('creates video peninggalan successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);

            $videoFile = UploadedFile::fake()->create('video.mp4', 1024); // 1MB video
            $thumbnailFile = UploadedFile::fake()->image('thumbnail.jpg', 640, 480); // 640x480 image

            $response = $this->actingAs($admin)->post(route('admin.video-peninggalan.store'), [
                'judul' => 'Test Video',
                'deskripsi' => 'Test deskripsi video',
                'link' => $videoFile,
                'thumbnail' => $thumbnailFile,
            ]);

            $response->assertRedirect(route('admin.video-peninggalan.index'));
            $response->assertSessionHas('success');
            $this->assertDatabaseHas('video_peninggalans', ['judul' => 'Test Video']);
        });

        it('validates required fields', function () {
            $admin = User::factory()->create(['role' => 'admin']);

            $response = $this->actingAs($admin)->post(route('admin.video-peninggalan.store'), []);

            $response->assertSessionHasErrors(['judul', 'deskripsi', 'link', 'thumbnail']);
        });
    });

    describe('GET /admin/video-peninggalan/{id}/edit', function () {
        it('displays video peninggalan edit form', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $video = VideoPeninggalan::factory()->create();

            $response = $this->actingAs($admin)->get(route('admin.video-peninggalan.edit', $video->id));

            $response->assertStatus(200);
            $response->assertViewIs('admin.video-peninggalan.edit');
            $response->assertViewHas('video');
        });
    });

    describe('PUT /admin/video-peninggalan/{id}', function () {
        it('updates video peninggalan successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $video = VideoPeninggalan::factory()->create();

            $response = $this->actingAs($admin)->put(route('admin.video-peninggalan.update', $video->id), [
                'judul' => 'Updated Video Title',
                'deskripsi' => 'Updated deskripsi',
            ]);

            $response->assertRedirect(route('admin.video-peninggalan.index'));
            $response->assertSessionHas('success');
            $this->assertDatabaseHas('video_peninggalans', [
                'id' => $video->id,
                'judul' => 'Updated Video Title',
            ]);
        });
    });

    describe('DELETE /admin/video-peninggalan/{id}', function () {
        it('deletes video peninggalan successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $video = VideoPeninggalan::factory()->create();

            $response = $this->actingAs($admin)->delete(route('admin.video-peninggalan.destroy', $video->id));

            $response->assertRedirect(route('admin.video-peninggalan.index'));
            $response->assertSessionHas('success');
            $this->assertDatabaseMissing('video_peninggalans', ['id' => $video->id]);
        });
    });
});
