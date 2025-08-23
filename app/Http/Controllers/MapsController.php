<?php

namespace App\Http\Controllers;

use App\Models\SitusPeninggalan;
use App\Models\User;
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
        return view('guest.maps.peninggalan');
    }
}
