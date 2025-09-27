<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoPeninggalanRequest;
use App\Models\VideoPeninggalan;
use Illuminate\Support\Facades\Storage;

class VideoPeninggalanController extends Controller
{
    public function index()
    {
        $data = VideoPeninggalan::all();
        return view('admin.video-peninggalan.index', compact('data'));
    }

    public function create()
    {
        return view('admin.video-peninggalan.create');
    }

    public function store(VideoPeninggalanRequest $request)
    {
        $data = $request->validated();

        // Handle video file upload
        if ($request->hasFile('link')) {
            $data['link'] = $request->file('link')->store('video-peninggalan/videos', 'public');
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('video-peninggalan/thumbnails', 'public');
        }

        VideoPeninggalan::create($data);

        return redirect()->route('admin.video-peninggalan.index')
            ->with('success', 'Video peninggalan berhasil ditambahkan.');
    }

    public function show(VideoPeninggalan $videoPeninggalan)
    {
        return $videoPeninggalan;
    }

    public function edit(string $id)
    {
        $video = VideoPeninggalan::findOrFail($id);
        return view('admin.video-peninggalan.edit', compact('video'));
    }

    public function update(VideoPeninggalanRequest $request, string $id)
    {
        $video = VideoPeninggalan::findOrFail($id);
        $data = $request->validated();

        // Handle video file upload
        if ($request->hasFile('link')) {
            // Delete old video file if exists
            if ($video->link && Storage::disk('public')->exists($video->link)) {
                Storage::disk('public')->delete($video->link);
            }

            $data['link'] = $request->file('link')->store('video-peninggalan/videos', 'public');
        } else {
            // Keep existing video file if no new file uploaded
            unset($data['link']);
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
                Storage::disk('public')->delete($video->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail')->store('video-peninggalan/thumbnails', 'public');
        } else {
            // Keep existing thumbnail if no new file uploaded
            unset($data['thumbnail']);
        }

        $video->update($data);

        return redirect()->route('admin.video-peninggalan.index')
            ->with('success', 'Video peninggalan berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        $video = VideoPeninggalan::findOrFail($id);

        // Delete video file if exists
        if ($video->link && Storage::disk('public')->exists($video->link)) {
            Storage::disk('public')->delete($video->link);
        }

        // Delete thumbnail file if exists
        if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        $video->delete();

        return redirect()->route('admin.video-peninggalan.index')
            ->with('success', 'Video peninggalan berhasil dihapus.');
    }
}
