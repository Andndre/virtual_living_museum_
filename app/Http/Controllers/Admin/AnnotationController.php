<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnotationRequest;
use App\Http\Requests\UpdateAnnotationRequest;
use App\Models\ModelAnnotation;
use App\Models\VirtualMuseum;
use Illuminate\Http\Request;

class AnnotationController extends Controller
{
    public function index(Request $request)
    {
        $query = ModelAnnotation::with('museum')->orderBy('display_order');

        if ($request->museum_id) {
            $query->where('museum_id', $request->museum_id);
        }

        $annotations = $query->paginate(20);
        $museums = VirtualMuseum::all();

        return view('admin.annotations.index', compact('annotations', 'museums'));
    }

    public function create(Request $request)
    {
        $museumId = $request->query('museum_id');
        $museums = VirtualMuseum::all();
        $selectedMuseum = $museumId ? VirtualMuseum::find($museumId) : null;

        return view('admin.annotations.create', compact('museums', 'selectedMuseum'));
    }

    public function store(StoreAnnotationRequest $request)
    {
        ModelAnnotation::create($request->validated());

        return redirect()->route('admin.annotations.index')
            ->with('success', 'Label berhasil disimpan.');
    }

    public function edit(ModelAnnotation $annotation)
    {
        $museums = VirtualMuseum::all();

        return view('admin.annotations.edit', compact('annotation', 'museums'));
    }

    public function update(UpdateAnnotationRequest $request, ModelAnnotation $annotation)
    {
        $annotation->update($request->validated());

        return redirect()->route('admin.annotations.index')
            ->with('success', 'Label berhasil diperbarui.');
    }

    public function destroy(ModelAnnotation $annotation)
    {
        $annotation->delete();

        return redirect()->route('admin.annotations.index')
            ->with('success', 'Label berhasil dihapus.');
    }
}
