<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

describe('Chunk Upload', function () {
  beforeEach(function () {
    Storage::fake('local');
  });

  describe('POST /admin/chunk-upload/chunk', function () {
    it('accepts chunk upload from admin', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $file = UploadedFile::fake()->create('model.glb', 1024);

      $response = $this->actingAs($admin)->post(route('admin.chunk-upload.chunk'), [
        'file' => $file,
        'uuid' => Str::uuid()->toString(),
        'chunkIndex' => 0,
        'totalChunks' => 1,
        'fieldName' => 'path_obj',
      ]);

      $response->assertJson(['received' => true]);
    });

    it('rejects chunk upload from non-admin users', function () {
      $user = User::factory()->create(['role' => 'user']);
      $file = UploadedFile::fake()->create('model.glb', 1024);

      $response = $this->actingAs($user)->post(route('admin.chunk-upload.chunk'), [
        'file' => $file,
        'uuid' => Str::uuid()->toString(),
        'chunkIndex' => 0,
        'totalChunks' => 1,
        'fieldName' => 'path_obj',
      ]);

      $response->assertRedirect(route('guest.home'));
    });
  });

  describe('POST /admin/chunk-upload/finalize', function () {
    it('finalizes chunk upload from admin', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $uuid = Str::uuid()->toString();

      $response = $this->actingAs($admin)->post(route('admin.chunk-upload.finalize'), [
        'uuid' => $uuid,
        'filename' => 'test-model.glb',
        'totalChunks' => 1,
        'fieldName' => 'path_obj',
      ]);

      // Will return error since no chunks exist - this is expected behavior
      $response->assertJsonFragment(['error' => 'No chunks found.']);
    });
  });

  describe('GET /admin/chunk-upload/status', function () {
    it('returns chunk upload status to admin', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $uuid = Str::uuid()->toString();

      $response = $this->actingAs($admin)->get(route('admin.chunk-upload.status').'?uuid='.$uuid.'&fieldName=path_obj&totalChunks=1');

      $response->assertJson(['complete' => false]);
    });
  });

  describe('DELETE /admin/chunk-upload/abort', function () {
    it('aborts chunk upload from admin', function () {
      $admin = User::factory()->create(['role' => 'admin']);
      $uuid = Str::uuid()->toString();

      $response = $this->actingAs($admin)->delete(route('admin.chunk-upload.abort'), [
        'uuid' => $uuid,
        'fieldName' => 'path_obj',
      ]);

      $response->assertJson(['aborted' => true]);
    });
  });
});