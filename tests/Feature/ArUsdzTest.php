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

test('admin can upload virtual museum object with optional usdz file', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $situs = SitusPeninggalan::factory()->create();
    $museum = VirtualMuseum::factory()->create(['situs_id' => $situs->situs_id]);
    $marker = ArMarker::create([
        'situs_id' => $situs->situs_id,
        'museum_id' => $museum->museum_id,
        'nama' => 'Marker Candi',
        'path_patt' => 'patterns/candi.patt',
        'path_gambar_marker' => 'markers/candi.png',
    ]);

    $imageFile = UploadedFile::fake()->image('statue.jpg');
    $glbFile = UploadedFile::fake()->create('statue.glb', 1024, 'model/gltf-binary');
    $usdzFile = UploadedFile::fake()->create('statue.usdz', 1024, 'model/vnd.usdz+zip');

    $response = $this->actingAs($admin)->post(route('admin.virtual-museum-object.store', $museum->museum_id), [
        'nama' => 'Arca Ganesha',
        'deskripsi' => 'Arca peninggalan bersejarah.',
        'gambar_real' => $imageFile,
        'path_obj' => $glbFile,
        'path_usdz' => $usdzFile,
        'marker_id' => $marker->marker_id,
    ]);

    $response->assertRedirect(route('admin.virtual-museum.show', $museum->museum_id));

    $object = VirtualMuseumObject::where('nama', 'Arca Ganesha')->first();
    expect($object)->not->toBeNull();
    expect($object->path_usdz)->not->toBeNull();
    Storage::disk('public')->assertExists($object->path_usdz);
    Storage::disk('public')->assertExists($object->path_obj);
});

test('api returns correct model metadata including usdz url', function () {
    $user = User::factory()->create(['role' => 'user']);
    $situs = SitusPeninggalan::factory()->create();
    $museum = VirtualMuseum::factory()->create(['situs_id' => $situs->situs_id]);
    $marker = ArMarker::create([
        'situs_id' => $situs->situs_id,
        'museum_id' => $museum->museum_id,
        'nama' => 'Marker Test',
        'path_patt' => 'patterns/test.patt',
        'path_gambar_marker' => 'markers/test.png',
    ]);

    $object = VirtualMuseumObject::create([
        'situs_id' => $situs->situs_id,
        'museum_id' => $museum->museum_id,
        'marker_id' => $marker->marker_id,
        'nama' => 'Prasasti Kuno',
        'gambar_real' => 'virtual-museum/objects/images/prasasti.jpg',
        'path_patt' => 'patterns/test.patt',
        'path_gambar_marker' => 'markers/test.png',
        'deskripsi' => 'Deskripsi batu prasasti.',
        'path_obj' => 'virtual-museum/objects/models/prasasti.glb',
        'path_usdz' => 'virtual-museum/objects/models_usdz/prasasti.usdz',
    ]);

    $response = $this->actingAs($user)->getJson(route('api.ar.model', ['marker' => $marker->marker_id]));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'name' => 'Prasasti Kuno',
            'id' => $object->object_id,
        ]);

    $data = $response->json();
    expect($data['usdz_url'])->toContain('/usdz-file/prasasti.usdz');
    expect($data['model_url'])->toContain('/storage/virtual-museum/objects/models/prasasti.glb');
});

test('serves usdz file with strict apple quick look mime type', function () {
    $filename = 'sample_artifact.usdz';
    $dir = 'virtual-museum/objects/models_usdz';
    Storage::disk('public')->put("{$dir}/{$filename}", 'FAKE_USDZ_CONTENT');

    $response = $this->get(route('usdz.serve', ['path' => $filename]));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'model/vnd.usdz+zip');
});

test('scan redirect route forwards to ar camera with marker param', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get(route('ar.scan.redirect', ['markerId' => '42']));

    $response->assertRedirect(route('ar-marker.camera', ['marker' => '42']));
});

test('admin can access ar scan redirect and camera without being booted to admin dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $redirectResponse = $this->actingAs($admin)->get(route('ar.scan.redirect', ['markerId' => '99']));
    $redirectResponse->assertRedirect(route('ar-marker.camera', ['marker' => '99']));

    $cameraResponse = $this->actingAs($admin)->get(route('ar-marker.camera', ['marker' => '99']));
    $cameraResponse->assertOk();
    $cameraResponse->assertSee('AR Scanner');
});

test('unauthenticated guest is redirected to login when accessing ar scan or camera', function () {
    $this->get(route('ar.scan.redirect', ['markerId' => '100']))->assertRedirect('/login');
    $this->get(route('ar-marker.camera', ['marker' => '100']))->assertRedirect('/login');
});
