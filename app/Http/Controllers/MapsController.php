<?php

namespace App\Http\Controllers;

use App\Models\Materi;
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

            // mendapatkan semua situs yang dimana user sudah menyelesaikannya
            $situs = SitusPeninggalan::whereHas('materi', function ($query) use ($level) {
                $query->where('urutan', '<=', $level);
            })->get();
            
            return view('guest.maps.view', compact('situs'));
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
