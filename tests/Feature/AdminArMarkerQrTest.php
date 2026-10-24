<?php

use App\Models\ArMarker;
use App\Models\SitusPeninggalan;
use App\Models\User;
use App\Models\VirtualMuseum;
use App\Models\VirtualMuseumObject;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

test('admin can create virtual museum object with a new marker name without patt file', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $situs = SitusPeninggalan::factory()->create();
    $museum = VirtualMuseum::factory()->create(['situs_id' => $situs->situs_id]);

    $imageFile = UploadedFile::fake()->image('statue.jpg');
    $glbFile = UploadedFile::fake()->create('statue.glb', 1024, 'model/gltf-binary');

    $response = $this->actingAs($admin)->post(route('admin.virtual-museum-object.store', $museum->museum_id), [
        'nama' => 'Objek QR Modern',
        'deskripsi' => 'Pengujian tanpa file patt',
        'gambar_real' => $imageFile,
        'path_obj' => $glbFile,
        'nama_marker' => 'Marker Otomatis Baru',
    ]);

    $response->assertRedirect(route('admin.virtual-museum.show', $museum->museum_id));

    $object = VirtualMuseumObject::where('nama', 'Objek QR Modern')->first();
    expect($object)->not->toBeNull();
    expect($object->marker_id)->not->toBeNull();
    expect($object->path_patt)->toBeNull();

    $marker = ArMarker::find($object->marker_id);
    expect($marker)->not->toBeNull();
    expect($marker->nama)->toBe('Marker Otomatis Baru');
});

test('admin can view object details and edit page with qr marker settings', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $situs = SitusPeninggalan::factory()->create();
    $museum = VirtualMuseum::factory()->create(['situs_id' => $situs->situs_id]);
    $marker = ArMarker::create([
        'situs_id' => $situs->situs_id,
        'museum_id' => $museum->museum_id,
        'nama' => 'Marker Candi Arjuna',
    ]);

    $object = VirtualMuseumObject::factory()->create([
        'situs_id' => $situs->situs_id,
        'museum_id' => $museum->museum_id,
        'marker_id' => $marker->marker_id,
        'nama' => 'Arca Siwa',
        'deskripsi' => 'Arca dewa Siwa',
    ]);

    $showResponse = $this->actingAs($admin)->get(route('admin.virtual-museum-object.show', $object->object_id));
    $showResponse->assertOk();
    $showResponse->assertSee('Marker AR (QR Code)');
    $showResponse->assertSee($marker->nama);
    $showResponse->assertSee(url('/ar/scan/'.$marker->marker_id));

    $editResponse = $this->actingAs($admin)->get(route('admin.virtual-museum-object.edit', $object->object_id));
    $editResponse->assertOk();
    $editResponse->assertSee('Pengaturan Marker AR (QR Code)');
    $editResponse->assertSee('qrious.min.js');
});

test('admin can update object marker to an existing marker or detach it', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $situs = SitusPeninggalan::factory()->create();
    $museum = VirtualMuseum::factory()->create(['situs_id' => $situs->situs_id]);
    $marker1 = ArMarker::create([
        'situs_id' => $situs->situs_id,
        'museum_id' => $museum->museum_id,
        'nama' => 'Marker 1',
    ]);
    $marker2 = ArMarker::create([
        'situs_id' => $situs->situs_id,
        'museum_id' => $museum->museum_id,
        'nama' => 'Marker 2',
    ]);

    $object = VirtualMuseumObject::factory()->create([
        'situs_id' => $situs->situs_id,
        'museum_id' => $museum->museum_id,
        'marker_id' => $marker1->marker_id,
        'nama' => 'Prasasti Kuno',
    ]);

    // Update to marker 2
    $response = $this->actingAs($admin)->put(route('admin.virtual-museum-object.update', $object->object_id), [
        'nama' => 'Prasasti Kuno Updated',
        'marker_id' => $marker2->marker_id,
    ]);

    $response->assertRedirect(route('admin.virtual-museum-object.show', $object->object_id));
    $object->refresh();
    expect($object->marker_id)->toBe($marker2->marker_id);
});
