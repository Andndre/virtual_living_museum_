<?php

namespace App\Http\Controllers;

use App\Models\SitusPeninggalan;
use App\Models\User;
use App\Models\VirtualMuseumObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MapsController extends Controller
{
    public function view(Request $request)
    {
        try {
            $userAuth = Auth::user();
            $user = User::findOrFail($userAuth->id);

            $progress = $user->progress_level_sekarang;
            $level = $user->level_sekarang;

            // Mendapatkan semua situs
            $allSitus = SitusPeninggalan::all();

            // Mengidentifikasi situs yang telah dibuka oleh user
            $unlockedSitusIds = SitusPeninggalan::whereHas('materi', function ($query) use ($level) {
                $query->where('urutan', '<=', $level);
            })->pluck('situs_id')->toArray();

            return view('guest.maps.view', compact('allSitus', 'unlockedSitusIds'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Gagal memuat halaman peta.');
        }
    }

    public function peninggalan(Request $request)
    {
        try {
            $userAuth = Auth::user();
            $user = User::findOrFail($userAuth->id);
            $searchQuery = $request->query('q', '');

            $level = $user->level_sekarang;

            // Get unlocked situs IDs
            $unlockedSitusIds = SitusPeninggalan::whereHas('materi', function ($query) use ($level) {
                $query->where('urutan', '<=', $level);
            })->pluck('situs_id')->toArray();

            // Get all virtual living museum objects with their related sites
            $objects = VirtualMuseumObject::with('situsPeninggalan', 'virtualMuseum')
                ->when($searchQuery, function($query) use ($searchQuery) {
                    $query->where('nama', 'like', "%{$searchQuery}%")
                          ->orWhere('deskripsi', 'like', "%{$searchQuery}%");
                })
                ->get()
                ->map(function ($object) use ($unlockedSitusIds) {
                    $object->is_unlocked = in_array($object->situs_id, $unlockedSitusIds);
                    $object->type = 'object';
                    return $object;
                });

            // Get all virtual museums with their related sites
            $museums = \App\Models\VirtualMuseum::with('situsPeninggalan')
                ->when($searchQuery, function($query) use ($searchQuery) {
                    $query->where('nama', 'like', "%{$searchQuery}%");
                })
                ->get()
                ->map(function ($museum) use ($unlockedSitusIds) {
                    $museum->is_unlocked = in_array($museum->situs_id, $unlockedSitusIds);
                    $museum->type = 'museum';
                    return $museum;
                });

            // Get all situs peninggalan
            $situsList = SitusPeninggalan::when($searchQuery, function($query) use ($searchQuery) {
                    $query->where('nama', 'like', "%{$searchQuery}%")
                          ->orWhere('deskripsi', 'like', "%{$searchQuery}%");
                })
                ->get()
                ->map(function ($situs) use ($unlockedSitusIds) {
                    $situs->is_unlocked = in_array($situs->situs_id, $unlockedSitusIds);
                    $situs->type = 'situs';
                    return $situs;
                });

            // Combine all results
            $allResults = collect()
                ->merge($objects)
                ->merge($museums)
                ->merge($situsList);

            // If there's a search query, filter the results
            if ($searchQuery) {
                $allResults = $allResults->filter(function($item) use ($searchQuery) {
                    $searchLower = strtolower($searchQuery);
                    $name = strtolower($item->nama);
                    $description = isset($item->deskripsi) ? strtolower($item->deskripsi) : '';
                    
                    return str_contains($name, $searchLower) || 
                           str_contains($description, $searchLower);
                });
            }

            // Get all unique situs for filtering
            $situs = SitusPeninggalan::all();

            return view('guest.maps.peninggalan', [
                'objects' => $allResults,
                'situs' => $situs,
                'unlockedSitusIds' => $unlockedSitusIds,
                'searchQuery' => $searchQuery
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Gagal memuat halaman peninggalan.');
        }
    }
}
