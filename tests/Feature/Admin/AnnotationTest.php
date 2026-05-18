<?php

use App\Models\ModelAnnotation;
use App\Models\User;
use App\Models\VirtualMuseum;

describe('Annotations Management', function () {
    describe('GET /admin/annotations', function () {
        it('displays annotations list to admin', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $museum = VirtualMuseum::factory()->create();
            ModelAnnotation::factory()->count(3)->create([
                'museum_id' => $museum->museum_id,
            ]);

            $response = $this->actingAs($admin)->get(route('admin.annotations.index'));

            $response->assertStatus(200);
            $response->assertViewIs('admin.annotations.index');
            $response->assertViewHas('annotations');
        });
    });

    describe('GET /admin/annotations/create', function () {
        it('displays annotation create form', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            VirtualMuseum::factory()->create();

            $response = $this->actingAs($admin)->get(route('admin.annotations.create'));

            $response->assertStatus(200);
            $response->assertViewIs('admin.annotations.create');
        });
    });

    describe('POST /admin/annotations', function () {
        it('creates annotation successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $museum = VirtualMuseum::factory()->create();

            $response = $this->actingAs($admin)->post(route('admin.annotations.store'), [
                'museum_id' => $museum->museum_id,
                'label' => 'Test Label',
                'position_x' => 1.0,
                'position_y' => 2.0,
                'position_z' => 3.0,
            ]);

            $response->assertRedirect(route('admin.annotations.index'));
            $response->assertSessionHas('success');
            $this->assertDatabaseHas('model_annotations', ['label' => 'Test Label']);
        });

        it('validates required fields', function () {
            $admin = User::factory()->create(['role' => 'admin']);

            $response = $this->actingAs($admin)->post(route('admin.annotations.store'), []);

            $response->assertSessionHasErrors(['museum_id', 'label']);
        });
    });

    describe('GET /admin/annotations/{annotation}/edit', function () {
        it('displays annotation edit form', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $museum = VirtualMuseum::factory()->create();
            $annotation = ModelAnnotation::factory()->create(['museum_id' => $museum->museum_id]);

            $response = $this->actingAs($admin)->get(route('admin.annotations.edit', $annotation->annotation_id));

            $response->assertStatus(200);
            $response->assertViewIs('admin.annotations.edit');
            $response->assertViewHas('annotation');
        });
    });

    describe('PUT /admin/annotations/{annotation}', function () {
        it('updates annotation successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $museum = VirtualMuseum::factory()->create();
            $annotation = ModelAnnotation::factory()->create(['museum_id' => $museum->museum_id]);

            $response = $this->actingAs($admin)->put(route('admin.annotations.update', $annotation->annotation_id), [
                'museum_id' => $museum->museum_id,
                'label' => 'Updated Label',
                'position_x' => 10.0,
                'position_y' => 20.0,
                'position_z' => 30.0,
            ]);

            $response->assertRedirect(route('admin.annotations.index'));
            $response->assertSessionHas('success');
            $this->assertDatabaseHas('model_annotations', [
                'annotation_id' => $annotation->annotation_id,
                'label' => 'Updated Label',
            ]);
        });
    });

    describe('DELETE /admin/annotations/{annotation}', function () {
        it('deletes annotation successfully', function () {
            $admin = User::factory()->create(['role' => 'admin']);
            $museum = VirtualMuseum::factory()->create();
            $annotation = ModelAnnotation::factory()->create(['museum_id' => $museum->museum_id]);

            $response = $this->actingAs($admin)->delete(route('admin.annotations.destroy', $annotation->annotation_id));

            $response->assertRedirect(route('admin.annotations.index'));
            $response->assertSessionHas('success');
            $this->assertDatabaseMissing('model_annotations', ['annotation_id' => $annotation->annotation_id]);
        });
    });
});
