<?php

namespace App\Http\Controllers;

use App\Models\ArMarker;
use App\Models\VirtualMuseumObject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ArMarkerCameraController extends Controller
{
    /**
     * Display the AR Marker camera view.
     */
    public function index(Request $request)
    {
        $initialMarker = $request->query('marker');
        $initialObject = $request->query('object');

        $arMarkers = ArMarker::with(['virtualMuseumObjects' => function ($query) {
            $query->whereNotNull('path_obj')->orderBy('object_id');
        }])
            ->orderBy('marker_id')
            ->get();

        return view('guest.ar-camera', compact('arMarkers', 'initialMarker', 'initialObject'));
    }

    /**
     * Fetch model metadata by marker or object ID for the AR scanner / viewer.
     */
    public function getModel(Request $request): JsonResponse
    {
        $request->validate([
            'marker' => 'nullable|string|max:255',
            'id' => 'nullable|integer',
        ]);

        $object = null;

        if ($request->filled('id')) {
            $object = VirtualMuseumObject::with('arMarker')->find($request->id);
        }

        if (! $object && $request->filled('marker')) {
            $rawMarker = trim((string) $request->marker);

            // Handle URL format: extract marker id if scanned as URL
            if (preg_match('/(?:ar\/scan\/|marker[=\/])(\d+)/i', $rawMarker, $matches)) {
                $markerId = (int) $matches[1];
            } elseif (is_numeric($rawMarker)) {
                $markerId = (int) $rawMarker;
            } else {
                $markerId = null;
            }

            if ($markerId) {
                // Find first object tied to this marker
                $object = VirtualMuseumObject::with('arMarker')
                    ->where('marker_id', $markerId)
                    ->whereNotNull('path_obj')
                    ->first();

                // If not found by marker_id directly, try by object_id
                if (! $object) {
                    $object = VirtualMuseumObject::with('arMarker')
                        ->where('object_id', $markerId)
                        ->whereNotNull('path_obj')
                        ->first();
                }
            }
        }

        if (! $object || ! $object->path_obj) {
            return response()->json([
                'success' => false,
                'error' => 'Model 3D tidak ditemukan untuk marker ini.',
            ], 404);
        }

        $usdzUrl = null;
        if ($object->path_usdz) {
            $usdzUrl = route('usdz.serve', ['path' => basename($object->path_usdz)]);
        }

        return response()->json([
            'success' => true,
            'id' => $object->object_id,
            'marker_id' => $object->marker_id,
            'name' => $object->nama,
            'description' => $object->deskripsi ?? '',
            'short_description' => Str::limit(strip_tags($object->deskripsi ?? ''), 120),
            'model_url' => asset('storage/'.$object->path_obj),
            'usdz_url' => $usdzUrl,
            'audio_url' => $object->path_audio ? asset('storage/'.$object->path_audio) : null,
            'gambar_real' => $object->gambar_real ? asset('storage/'.$object->gambar_real) : null,
        ]);
    }

    /**
     * Serve USDZ files with the strict MIME type required by Apple iOS AR Quick Look.
     */
    public function serveUsdz(string $path): BinaryFileResponse
    {
        $sanitized = basename($path);
        $relative = 'virtual-museum/objects/models_usdz/'.$sanitized;

        if (Storage::disk('public')->exists($relative)) {
            $fullPath = Storage::disk('public')->path($relative);
        } else {
            $fallback = 'virtual-museum/objects/models/'.$sanitized;
            if (Storage::disk('public')->exists($fallback)) {
                $fullPath = Storage::disk('public')->path($fallback);
            } else {
                abort(404, 'File USDZ tidak ditemukan.');
            }
        }

        $filename = $sanitized;
        if (! str_ends_with(strtolower($filename), '.usdz')) {
            $filename .= '.usdz';
        }

        return response()->file($fullPath, [
            'Content-Type' => 'model/vnd.usdz+zip',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
            'Access-Control-Allow-Origin' => '*',
            'Cache-Control' => 'public, max-age=31536000, immutable, no-transform',
        ]);
    }

    /**
     * Smart redirect for scanned QR code URLs (e.g. from native iOS camera).
     */
    public function scanRedirect(string $markerId): RedirectResponse
    {
        return redirect()->route('ar-marker.camera', ['marker' => $markerId]);
    }
}
