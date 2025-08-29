<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Katalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KatalogController extends Controller
{
    public function edit()
    {
        $katalog = Katalog::first();
        return view('admin.katalog.edit', compact('katalog'));
    }

    public function update(Request $request)
    {
        // Debug: Log the request data
        \Log::info('File upload request:', [
            'hasFile' => $request->hasFile('path_pdf'),
            'allFiles' => $request->allFiles(),
            'allInput' => $request->all()
        ]);

        $katalog = Katalog::firstOrNew(['id' => 1]);

        // Always validate the file if it's present
        $request->validate([
            'path_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        // Handle file upload if present
        if ($request->hasFile('path_pdf')) {
            $file = $request->file('path_pdf');

            // Delete old file if exists
            if ($katalog->path_pdf && Storage::disk('public')->exists($katalog->path_pdf)) {
                Storage::disk('public')->delete($katalog->path_pdf);
            }

            // Store new file
            $filename = 'katalog-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('katalog', $filename, 'public');
            $katalog->path_pdf = $path;
        } elseif (!$katalog->exists) {
            // Only require file for new records
            return back()->with('error', 'Silakan unggah file PDF');
        }

        $katalog->save();

        return redirect()->route('admin.katalog.edit')
            ->with('success', 'Katalog berhasil diperbarui');
    }
}
