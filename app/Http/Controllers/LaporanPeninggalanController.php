<?php

namespace App\Http\Controllers;

use App\Models\LaporanGambar;
use App\Models\LaporanKomentar;
use App\Models\LaporanPeninggalan;
use App\Models\LaporanSuka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanPeninggalanController extends Controller
{
    /**
     * Display a listing of the heritage reports.
     */
    public function index()
    {
        $userId = Auth::id();
        
        $laporan = LaporanPeninggalan::with([
                'user', 
                'laporanGambar', 
                'laporanSuka' => function($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ])
            ->withCount('laporanSuka as like_count')
            ->withCount('laporanKomentar as comment_count')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('guest.laporan-peninggalan.index', compact('laporan'));
    }

    /**
     * Show the form for creating a new heritage report.
     */
    public function create()
    {
        return view('guest.laporan-peninggalan.create');
    }

    /**
     * Store a newly created heritage report in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_peninggalan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'deskripsi' => 'required|string',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Create the report
        $laporan = LaporanPeninggalan::create([
            'user_id' => Auth::id(),
            'nama_peninggalan' => $request->nama_peninggalan,
            'alamat' => $request->alamat,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'deskripsi' => $request->deskripsi,
            'created_at' => now(),
        ]);

        // Store images if provided
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $image) {
                $path = $image->store('laporan_peninggalan', 'public');
                
                LaporanGambar::create([
                    'laporan_id' => $laporan->laporan_id,
                    'path_gambar' => $path,
                ]);
            }
        }

        return redirect()->route('guest.laporan-peninggalan.show', $laporan->laporan_id)
            ->with('success', 'Laporan peninggalan berhasil ditambahkan.');
    }

    /**
     * Display the specified heritage report.
     */
    public function show($id)
    {
        $laporan = LaporanPeninggalan::with(['user', 'laporanGambar', 'laporanKomentar.user'])
            ->withCount('laporanSuka as like_count')
            ->findOrFail($id);

        // Check if current user has liked this report
        $userLiked = LaporanSuka::where('laporan_id', $id)
            ->where('user_id', Auth::id())
            ->exists();

        return view('guest.laporan-peninggalan.show', compact('laporan', 'userLiked'));
    }

    /**
     * Toggle like on a heritage report.
     */
    public function toggleLike($id)
    {
        $laporan = LaporanPeninggalan::findOrFail($id);
        
        $existingLike = LaporanSuka::where('laporan_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingLike) {
            // Unlike if already liked
            $existingLike->delete();
            $action = 'unliked';
        } else {
            // Like if not already liked
            LaporanSuka::create([
                'laporan_id' => $id,
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);
            $action = 'liked';
        }

        if (request()->ajax()) {
            $likeCount = LaporanSuka::where('laporan_id', $id)->count();
            return response()->json([
                'action' => $action,
                'like_count' => $likeCount,
            ]);
        }

        return back()->with('success', $action === 'liked' ? 'Berhasil menyukai laporan' : 'Berhasil membatalkan suka');
    }

    /**
     * Store a comment on a heritage report.
     */
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string|max:500',
        ]);

        $laporan = LaporanPeninggalan::findOrFail($id);

        LaporanKomentar::create([
            'laporan_id' => $id,
            'user_id' => Auth::id(),
            'komentar' => $request->komentar,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
