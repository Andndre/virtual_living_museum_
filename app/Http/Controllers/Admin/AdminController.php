<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AksesSitusUser;
use App\Models\Ebook;
use App\Models\KritikSaran;
use App\Models\LaporanPeninggalan;
use App\Models\Materi;
use App\Models\Posttest;
use App\Models\Pretest;
use App\Models\SitusPeninggalan;
use App\Models\User;
use App\Models\VirtualMuseum;
use App\Models\VirtualMuseumObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        // Statistics
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_materi' => Materi::count(),
            'total_situs' => SitusPeninggalan::count(),
            'total_laporan' => LaporanPeninggalan::count(),
            'total_kritik_saran' => KritikSaran::count(),
        ];

        // Recent users (last 10)
        $recentUsers = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent reports (last 5)
        $recentReports = LaporanPeninggalan::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent feedback (last 5)
        $recentFeedback = KritikSaran::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Monthly user registrations (last 6 months)
        $monthlyUsers = User::where('role', 'user')
            ->select(DB::raw('COUNT(*) as count'), DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'))
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('YEAR(created_at)'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentReports',
            'recentFeedback',
            'monthlyUsers'
        ));
    }

    /**
     * Display users management
     */
    public function users()
    {
        $users = User::with('logAktivitas')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function showUser($id)
    {
        $user = User::with(['logAktivitas', 'jawabanUser', 'aksesSitusUser', 'kritikSaran', 'laporanPeninggalan'])
            ->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|in:user,admin',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['profile_photo', '_token', '_method', 'password_confirmation']);

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && Storage::exists('public/'.$user->profile_photo)) {
                Storage::delete('public/'.$user->profile_photo);
            }

            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
            $data['profile_photo'] = $photoPath;
        }

        $user->update($data);

        return redirect()->route('admin.users')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        // Delete profile photo if exists
        if ($user->profile_photo && Storage::exists('public/'.$user->profile_photo)) {
            Storage::delete('public/'.$user->profile_photo);
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'Pengguna berhasil dihapus!');
    }

    /**
     * Display materi management
     */
    public function materi()
    {
        $materis = Materi::orderBy('urutan', 'asc')->paginate(20);

        return view('admin.materi.index', compact('materis'));
    }

    /**
     * Display situs management
     */
    public function situs()
    {
        $situs = SitusPeninggalan::with(['user', 'materi', 'virtualMuseum', 'virtualMuseumObject', 'aksesSitusUser'])
            ->orderBy('situs_id', 'desc')
            ->paginate(20);

        // Calculate statistics
        $stats = [
            'total_situs' => SitusPeninggalan::count(),
            'total_virtual_objects' => VirtualMuseumObject::whereNotNull('situs_id')->count(),
            'total_user_accesses' => AksesSitusUser::count(),
        ];

        return view('admin.situs.index', compact('situs', 'stats'));
    }

    /**
     * Show the form for creating a new situs
     */
    public function createSitus()
    {
        $materis = Materi::orderBy('judul')->get();

        return view('admin.situs.create', compact('materis'));
    }

    /**
     * Store a newly created situs in storage
     */
    public function storeSitus(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'deskripsi' => 'required|string',
            'materi_id' => 'required|exists:materi,materi_id',
        ]);

        SitusPeninggalan::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'deskripsi' => $request->deskripsi,
            'materi_id' => $request->materi_id,
            'user_id' => Auth::id(), // Auto-set current user
        ]);

        return redirect()->route('admin.situs')->with('success', 'Situs peninggalan berhasil ditambahkan.');
    }

    /**
     * Display the specified situs
     */
    public function showSitus($situs_id)
    {
        $situs = SitusPeninggalan::with(['user', 'materi', 'virtualMuseum.virtualMuseumObjects', 'virtualMuseumObject', 'aksesSitusUser'])
            ->findOrFail($situs_id);

        $stats = [
            'virtual_objects' => $situs->virtualMuseumObject->count(),
            'user_accesses' => $situs->aksesSitusUser->count(),
        ];

        return view('admin.situs.show', compact('situs', 'stats'));
    }

    /**
     * Show the form for editing the specified situs
     */
    public function editSitus($situs_id)
    {
        $situs = SitusPeninggalan::findOrFail($situs_id);
        $materis = Materi::orderBy('judul')->get();

        return view('admin.situs.edit', compact('situs', 'materis'));
    }

    /**
     * Update the specified situs in storage
     */
    public function updateSitus(Request $request, $situs_id)
    {
        $situs = SitusPeninggalan::findOrFail($situs_id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'deskripsi' => 'required|string',
            'materi_id' => 'required|exists:materi,materi_id',
        ]);

        $situs->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'deskripsi' => $request->deskripsi,
            'materi_id' => $request->materi_id,
            // user_id tetap tidak berubah saat edit
        ]);

        return redirect()->route('admin.situs.show', $situs_id)->with('success', 'Situs peninggalan berhasil diperbarui.');
    }

    /**
     * Remove the specified situs from storage
     */
    public function destroySitus($situs_id)
    {
        $situs = SitusPeninggalan::findOrFail($situs_id);
        $nama = $situs->nama;
        $situs->delete();

        return redirect()->route('admin.situs')->with('success', "Situs peninggalan '{$nama}' berhasil dihapus.");
    }

    // =======================
    // VIRTUAL MUSEUM MANAGEMENT
    // =======================

    /**
     * Display virtual museum management
     */
    public function virtualMuseum()
    {
        $museums = VirtualMuseum::with(['situsPeninggalan', 'virtualMuseumObjects'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $stats = [
            'total_museums' => VirtualMuseum::count(),
            'total_objects' => VirtualMuseumObject::whereNotNull('museum_id')->count(),
            'total_situs_with_museum' => SitusPeninggalan::whereHas('virtualMuseum')->count(),
            'total_situs_without_museum' => SitusPeninggalan::whereDoesntHave('virtualMuseum')->count(),
        ];

        return view('admin.virtual-museum.index', compact('museums', 'stats'));
    }

    /**
     * Show the form for creating a new virtual museum
     */
    public function createVirtualMuseum(Request $request)
    {
        $situsOptions = SitusPeninggalan::orderBy('nama')->get();

        // Pre-select situs if provided in query parameter
        $selectedSitusId = $request->get('situs_id');

        return view('admin.virtual-museum.create', compact('situsOptions', 'selectedSitusId'));
    }

    /**
     * Store a newly created virtual museum
     */
    public function storeVirtualMuseum(Request $request)
    {
        $request->validate([
            'situs_id' => 'required|exists:situs_peninggalan,situs_id',
            'nama' => 'required|string|max:255',
            'obj_file' => 'required|file|max:307200', // 300MB max, any file type
        ]);

        $data = [
            'situs_id' => $request->situs_id,
            'nama' => $request->nama,
        ];

        // Handle file upload
        if ($request->hasFile('obj_file')) {
            $file = $request->file('obj_file');
            $filename = time().'_'.preg_replace('/[^a-zA-Z0-9\._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('virtual-museum/models', $filename, 'public');
            $data['path_obj'] = $path;
        }

        VirtualMuseum::create($data);

        return redirect()->route('admin.virtual-museum')
            ->with('success', 'Virtual Museum berhasil ditambahkan!');
    }

    /**
     * Display the specified virtual museum
     */
    public function showVirtualMuseum($museum_id)
    {
        $museum = VirtualMuseum::with([
            'situsPeninggalan.materi',
            'virtualMuseumObjects',
        ])->findOrFail($museum_id);

        return view('admin.virtual-museum.show', compact('museum'));
    }

    /**
     * Show the form for editing virtual museum
     */
    public function editVirtualMuseum($museum_id)
    {
        $museum = VirtualMuseum::with('situsPeninggalan')->findOrFail($museum_id);

        // Get all situs - now supporting multiple museums per situs
        $situsOptions = SitusPeninggalan::orderBy('nama')->get();

        return view('admin.virtual-museum.edit', compact('museum', 'situsOptions'));
    }

    /**
     * Update the specified virtual museum
     */
    public function updateVirtualMuseum(Request $request, $museum_id)
    {
        $museum = VirtualMuseum::findOrFail($museum_id);

        $request->validate([
            'situs_id' => 'required|exists:situs_peninggalan,situs_id',
            'nama' => 'required|string|max:255',
            'obj_file' => 'nullable|file|max:307200', // 300MB max, optional for update, any file type
        ]);

        $data = [
            'situs_id' => $request->situs_id,
            'nama' => $request->nama,
        ];

        // Handle file upload if new file is provided
        if ($request->hasFile('obj_file')) {
            // Delete old file if it exists
            if ($museum->path_obj && Storage::disk('public')->exists($museum->path_obj)) {
                Storage::disk('public')->delete($museum->path_obj);
            }

            $file = $request->file('obj_file');
            $filename = time().'_'.preg_replace('/[^a-zA-Z0-9\._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('virtual-museum/models', $filename, 'public');
            $data['path_obj'] = $path;
        }

        $museum->update($data);

        return redirect()->route('admin.virtual-museum.show', $museum_id)
            ->with('success', 'Virtual Museum berhasil diperbarui!');
    }

    /**
     * Remove the specified virtual museum
     */
    public function destroyVirtualMuseum($museum_id)
    {
        $museum = VirtualMuseum::findOrFail($museum_id);
        $nama = $museum->nama;
        $museum->delete();

        return redirect()->route('admin.virtual-museum')
            ->with('success', "Virtual Museum '{$nama}' berhasil dihapus.");
    }

    /**
     * Show the form for creating a new virtual museum object
     */
    public function createVirtualMuseumObject($museum_id)
    {
        $museum = VirtualMuseum::with('situsPeninggalan')->findOrFail($museum_id);

        return view('admin.virtual-museum-object.create', compact('museum'));
    }

    /**
     * Store a newly created virtual museum object
     */
    public function storeVirtualMuseumObject(Request $request, $museum_id)
    {
        $museum = VirtualMuseum::findOrFail($museum_id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar_real' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
            'path_obj' => 'nullable|file|max:307200', // 300MB
            'path_patt' => 'nullable|file|max:10240', // 10MB
            'path_gambar_marker' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
        ]);

        $data = [
            'situs_id' => $museum->situs_id,
            'museum_id' => $museum_id,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ];

        // Handle file uploads
        if ($request->hasFile('gambar_real')) {
            $file = $request->file('gambar_real');
            $filename = time().'_gambar_real_'.preg_replace('/[^a-zA-Z0-9\._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('virtual-museum/objects/images', $filename, 'public');
            $data['gambar_real'] = $path;
        }

        if ($request->hasFile('path_obj')) {
            $file = $request->file('path_obj');
            $filename = time().'_obj_'.preg_replace('/[^a-zA-Z0-9\._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('virtual-museum/objects/models', $filename, 'public');
            $data['path_obj'] = $path;
        }

        if ($request->hasFile('path_patt')) {
            $file = $request->file('path_patt');
            $filename = time().'_patt_'.preg_replace('/[^a-zA-Z0-9\._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('virtual-museum/objects/patterns', $filename, 'public');
            $data['path_patt'] = $path;
        }

        if ($request->hasFile('path_gambar_marker')) {
            $file = $request->file('path_gambar_marker');
            $filename = time().'_marker_'.preg_replace('/[^a-zA-Z0-9\._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('virtual-museum/objects/markers', $filename, 'public');
            $data['path_gambar_marker'] = $path;
        }

        VirtualMuseumObject::create($data);

        return redirect()->route('admin.virtual-museum.show', $museum_id)
            ->with('success', 'Object Virtual Museum berhasil ditambahkan!');
    }

    /**
     * Display the specified virtual museum object
     */
    public function showVirtualMuseumObject($object_id)
    {
        $object = VirtualMuseumObject::with(['situsPeninggalan', 'virtualMuseum.situsPeninggalan'])->findOrFail($object_id);

        return view('admin.virtual-museum-object.show', compact('object'));
    }

    /**
     * Show the form for editing the specified virtual museum object
     */
    public function editVirtualMuseumObject($object_id)
    {
        $object = VirtualMuseumObject::with(['situsPeninggalan', 'virtualMuseum.situsPeninggalan'])->findOrFail($object_id);

        return view('admin.virtual-museum-object.edit', compact('object'));
    }

    /**
     * Update the specified virtual museum object
     */
    public function updateVirtualMuseumObject(Request $request, $object_id)
    {
        $object = VirtualMuseumObject::findOrFail($object_id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar_real' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
            'path_obj' => 'nullable|file|max:307200', // 300MB
            'path_patt' => 'nullable|file|max:10240', // 10MB
            'path_gambar_marker' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
        ]);

        $data = [
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ];

        // Handle file uploads
        if ($request->hasFile('gambar_real')) {
            // Delete old file if it exists
            if ($object->gambar_real && Storage::disk('public')->exists($object->gambar_real)) {
                Storage::disk('public')->delete($object->gambar_real);
            }

            $file = $request->file('gambar_real');
            $filename = time().'_gambar_real_'.preg_replace('/[^a-zA-Z0-9\._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('virtual-museum/objects/images', $filename, 'public');
            $data['gambar_real'] = $path;
        }

        if ($request->hasFile('path_obj')) {
            // Delete old file if it exists
            if ($object->path_obj && Storage::disk('public')->exists($object->path_obj)) {
                Storage::disk('public')->delete($object->path_obj);
            }

            $file = $request->file('path_obj');
            $filename = time().'_obj_'.preg_replace('/[^a-zA-Z0-9\._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('virtual-museum/objects/models', $filename, 'public');
            $data['path_obj'] = $path;
        }

        if ($request->hasFile('path_patt')) {
            // Delete old file if it exists
            if ($object->path_patt && Storage::disk('public')->exists($object->path_patt)) {
                Storage::disk('public')->delete($object->path_patt);
            }

            $file = $request->file('path_patt');
            $filename = time().'_patt_'.preg_replace('/[^a-zA-Z0-9\._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('virtual-museum/objects/patterns', $filename, 'public');
            $data['path_patt'] = $path;
        }

        if ($request->hasFile('path_gambar_marker')) {
            // Delete old file if it exists
            if ($object->path_gambar_marker && Storage::disk('public')->exists($object->path_gambar_marker)) {
                Storage::disk('public')->delete($object->path_gambar_marker);
            }

            $file = $request->file('path_gambar_marker');
            $filename = time().'_marker_'.preg_replace('/[^a-zA-Z0-9\._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('virtual-museum/objects/markers', $filename, 'public');
            $data['path_gambar_marker'] = $path;
        }

        $object->update($data);

        return redirect()->route('admin.virtual-museum-object.show', $object_id)
            ->with('success', 'Object Virtual Museum berhasil diperbarui!');
    }

    /**
     * Remove the specified virtual museum object
     */
    public function destroyVirtualMuseumObject($object_id)
    {
        $object = VirtualMuseumObject::findOrFail($object_id);
        $museum_id = $object->museum_id;
        $nama = $object->nama;

        // Delete associated files
        $files = ['gambar_real', 'path_obj', 'path_patt'];
        foreach ($files as $fileField) {
            if ($object->$fileField && Storage::disk('public')->exists($object->$fileField)) {
                Storage::disk('public')->delete($object->$fileField);
            }
        }

        $object->delete();

        return redirect()->route('admin.virtual-museum.show', $museum_id)
            ->with('success', "Object Virtual Museum '{$nama}' berhasil dihapus.");
    }

    /**
     * Display reports management
     */
    public function reports()
    {
        $reports = LaporanPeninggalan::with(['user', 'laporanGambar'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reports', compact('reports'));
    }

    /**
     * Display the specified report
     */
    public function showReport($id)
    {
        $report = LaporanPeninggalan::with(['user', 'laporanGambar', 'laporanKomentar.user', 'laporanSuka.user'])
            ->findOrFail($id);

        return view('admin.reports.show', compact('report'));
    }

    /**
     * Remove the specified report
     */
    public function destroyReport($id)
    {
        try {
            $report = LaporanPeninggalan::findOrFail($id);
            $nama = $report->nama_peninggalan;

            // Delete associated files
            foreach ($report->laporanGambar as $gambar) {
                if ($gambar->path_gambar && Storage::disk('public')->exists($gambar->path_gambar)) {
                    Storage::disk('public')->delete($gambar->path_gambar);
                }
            }

            $report->delete();

            return redirect()->route('admin.reports')
                ->with('success', "Laporan '{$nama}' berhasil dihapus.");

        } catch (\Exception $e) {
            return redirect()->route('admin.reports')
                ->with('error', 'Terjadi kesalahan saat menghapus laporan.');
        }
    }

    /**
     * Display feedback management
     */
    public function feedback()
    {
        $feedback = KritikSaran::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.feedback', compact('feedback'));
    }

    /**
     * Delete a feedback
     */
    public function destroyFeedback($id)
    {
        try {
            $feedback = KritikSaran::findOrFail($id);
            $feedback->delete();

            return redirect()->route('admin.feedback')
                ->with('success', 'Feedback berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.feedback')
                ->with('error', 'Gagal menghapus feedback: '.$e->getMessage());
        }
    }

    /**
     * Show the form for creating a new materi
     */
    public function createMateri()
    {
        return view('admin.materi.create');
    }

    /**
     * Store a newly created materi
     */
    public function storeMateri(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        // Auto generate urutan (get next available order)
        $nextUrutan = Materi::max('urutan') + 1;

        $data = $request->all();
        $data['urutan'] = $nextUrutan;

        Materi::create($data);

        return redirect()->route('admin.materi')
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    /**
     * Display the specified materi
     */
    public function showMateri($id)
    {
        $materi = Materi::with(['pretest', 'posttest', 'ebook', 'situsPeninggalan'])
            ->findOrFail($id);

        return view('admin.materi.show', compact('materi'));
    }

    /**
     * Show the form for editing materi
     */
    public function editMateri($id)
    {
        $materi = Materi::findOrFail($id);

        return view('admin.materi.edit', compact('materi'));
    }

    /**
     * Update the specified materi
     */
    public function updateMateri(Request $request, $id)
    {
        $materi = Materi::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $materi->update($request->only(['judul', 'deskripsi']));

        return redirect()->route('admin.materi')
            ->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Remove the specified materi
     */
    public function destroyMateri($id)
    {
        $materi = Materi::findOrFail($id);

        // Check if materi has related data
        if ($materi->pretest()->exists() || $materi->posttest()->exists() ||
            $materi->ebook()->exists() || $materi->situsPeninggalan()->exists()) {
            return redirect()->route('admin.materi')
                ->with('error', 'Tidak dapat menghapus materi yang memiliki data terkait!');
        }

        $materi->delete();

        return redirect()->route('admin.materi')
            ->with('success', 'Materi berhasil dihapus!');
    }

    /**
     * Update materi order via drag & drop
     */
    public function updateMateriOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:materi,materi_id',
            'items.*.position' => 'required|integer|min:1',
        ]);

        foreach ($request->items as $item) {
            Materi::where('materi_id', $item['id'])
                ->update(['urutan' => $item['position']]);
        }

        return response()->json(['message' => 'Urutan materi berhasil diperbarui!']);
    }

    // ========== PRETEST MANAGEMENT ==========

    /**
     * Display pretest for specific materi
     */
    public function pretest($materi_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $pretests = Pretest::where('materi_id', $materi_id)
            ->orderBy('pretest_id', 'asc')
            ->paginate(20);

        return view('admin.pretest.index', compact('materi', 'pretests'));
    }

    /**
     * Show the form for creating a new pretest
     */
    public function createPretest($materi_id)
    {
        $materi = Materi::findOrFail($materi_id);

        return view('admin.pretest.create', compact('materi'));
    }

    /**
     * Store a newly created pretest
     */
    public function storePretest(Request $request, $materi_id)
    {
        $materi = Materi::findOrFail($materi_id);

        $request->validate([
            'pertanyaan' => 'required|string',
            'pilihan_a' => 'required|string|max:255',
            'pilihan_b' => 'required|string|max:255',
            'pilihan_c' => 'required|string|max:255',
            'pilihan_d' => 'required|string|max:255',
            'jawaban_benar' => 'required|in:A,B,C,D',
        ]);

        $data = $request->all();
        $data['materi_id'] = $materi_id;

        Pretest::create($data);

        return redirect()->route('admin.pretest', $materi_id)
            ->with('success', 'Soal pretest berhasil ditambahkan!');
    }

    /**
     * Display the specified pretest
     */
    public function showPretest($materi_id, $pretest_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $pretest = Pretest::where('materi_id', $materi_id)->findOrFail($pretest_id);

        // Get previous and next pretest questions
        $previousPretest = Pretest::where('materi_id', $materi_id)
            ->where('pretest_id', '<', $pretest_id)
            ->orderBy('pretest_id', 'desc')
            ->first();

        $nextPretest = Pretest::where('materi_id', $materi_id)
            ->where('pretest_id', '>', $pretest_id)
            ->orderBy('pretest_id', 'asc')
            ->first();

        return view('admin.pretest.show', compact('materi', 'pretest', 'previousPretest', 'nextPretest'));
    }

    /**
     * Show the form for editing pretest
     */
    public function editPretest($materi_id, $pretest_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $pretest = Pretest::where('materi_id', $materi_id)->findOrFail($pretest_id);

        return view('admin.pretest.edit', compact('materi', 'pretest'));
    }

    /**
     * Update the specified pretest
     */
    public function updatePretest(Request $request, $materi_id, $pretest_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $pretest = Pretest::where('materi_id', $materi_id)->findOrFail($pretest_id);

        $request->validate([
            'pertanyaan' => 'required|string',
            'pilihan_a' => 'required|string|max:255',
            'pilihan_b' => 'required|string|max:255',
            'pilihan_c' => 'required|string|max:255',
            'pilihan_d' => 'required|string|max:255',
            'jawaban_benar' => 'required|in:A,B,C,D',
        ]);

        $pretest->update($request->all());

        return redirect()->route('admin.pretest', $materi_id)
            ->with('success', 'Soal pretest berhasil diperbarui!');
    }

    /**
     * Remove the specified pretest
     */
    public function destroyPretest($materi_id, $pretest_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $pretest = Pretest::where('materi_id', $materi_id)->findOrFail($pretest_id);

        $pretest->delete();

        return redirect()->route('admin.pretest', $materi_id)
            ->with('success', 'Soal pretest berhasil dihapus!');
    }

    // ========== POSTTEST MANAGEMENT ==========

    /**
     * Display posttest for specific materi
     */
    public function posttest($materi_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $posttests = Posttest::where('materi_id', $materi_id)
            ->orderBy('posttest_id', 'asc')
            ->paginate(20);

        return view('admin.posttest.index', compact('materi', 'posttests'));
    }

    /**
     * Show the form for creating a new posttest
     */
    public function createPosttest($materi_id)
    {
        $materi = Materi::findOrFail($materi_id);

        return view('admin.posttest.create', compact('materi'));
    }

    /**
     * Store a newly created posttest
     */
    public function storePosttest(Request $request, $materi_id)
    {
        $materi = Materi::findOrFail($materi_id);

        $request->validate([
            'pertanyaan' => 'required|string',
            'pilihan_a' => 'required|string|max:255',
            'pilihan_b' => 'required|string|max:255',
            'pilihan_c' => 'required|string|max:255',
            'pilihan_d' => 'required|string|max:255',
            'jawaban_benar' => 'required|in:A,B,C,D',
        ]);

        $data = $request->all();
        $data['materi_id'] = $materi_id;

        Posttest::create($data);

        return redirect()->route('admin.posttest', $materi_id)
            ->with('success', 'Soal posttest berhasil ditambahkan!');
    }

    /**
     * Display the specified posttest
     */
    public function showPosttest($materi_id, $posttest_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $posttest = Posttest::where('materi_id', $materi_id)->findOrFail($posttest_id);

        // Get previous and next posttest questions
        $previousPosttest = Posttest::where('materi_id', $materi_id)
            ->where('posttest_id', '<', $posttest_id)
            ->orderBy('posttest_id', 'desc')
            ->first();

        $nextPosttest = Posttest::where('materi_id', $materi_id)
            ->where('posttest_id', '>', $posttest_id)
            ->orderBy('posttest_id', 'asc')
            ->first();

        return view('admin.posttest.show', compact('materi', 'posttest', 'previousPosttest', 'nextPosttest'));
    }

    /**
     * Show the form for editing posttest
     */
    public function editPosttest($materi_id, $posttest_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $posttest = Posttest::where('materi_id', $materi_id)->findOrFail($posttest_id);

        return view('admin.posttest.edit', compact('materi', 'posttest'));
    }

    /**
     * Update the specified posttest
     */
    public function updatePosttest(Request $request, $materi_id, $posttest_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $posttest = Posttest::where('materi_id', $materi_id)->findOrFail($posttest_id);

        $request->validate([
            'pertanyaan' => 'required|string',
            'pilihan_a' => 'required|string|max:255',
            'pilihan_b' => 'required|string|max:255',
            'pilihan_c' => 'required|string|max:255',
            'pilihan_d' => 'required|string|max:255',
            'jawaban_benar' => 'required|in:A,B,C,D',
        ]);

        $posttest->update($request->all());

        return redirect()->route('admin.posttest', $materi_id)
            ->with('success', 'Soal posttest berhasil diperbarui!');
    }

    /**
     * Remove the specified posttest
     */
    public function destroyPosttest($materi_id, $posttest_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $posttest = Posttest::where('materi_id', $materi_id)->findOrFail($posttest_id);

        $posttest->delete();

        return redirect()->route('admin.posttest', $materi_id)
            ->with('success', 'Soal posttest berhasil dihapus!');
    }

    /**
     * Display ebook management for a specific materi
     */
    public function ebook($materi_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $ebooks = Ebook::where('materi_id', $materi_id)->paginate(10);

        return view('admin.ebook.index', compact('materi', 'ebooks'));
    }

    /**
     * Show the form for creating a new ebook
     */
    public function createEbook($materi_id)
    {
        $materi = Materi::findOrFail($materi_id);

        return view('admin.ebook.create', compact('materi'));
    }

    /**
     * Store a newly created ebook
     */
    public function storeEbook(Request $request, $materi_id)
    {
        $materi = Materi::findOrFail($materi_id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'path_file' => 'required|file|mimes:pdf|max:51200', // 50MB max for PDF
        ]);

        $data = [
            'materi_id' => $materi_id,
            'judul' => $request->judul,
        ];

        // Handle file upload
        if ($request->hasFile('path_file')) {
            $file = $request->file('path_file');
            $filename = time().'_ebook_'.preg_replace('/[^a-zA-Z0-9\._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('ebooks', $filename, 'public');
            $data['path_file'] = $path;
        }

        Ebook::create($data);

        return redirect()->route('admin.ebook', $materi_id)
            ->with('success', 'E-book berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified ebook
     */
    public function editEbook($materi_id, $ebook_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $ebook = Ebook::where('materi_id', $materi_id)->where('ebook_id', $ebook_id)->firstOrFail();

        return view('admin.ebook.edit', compact('materi', 'ebook'));
    }

    /**
     * Update the specified ebook
     */
    public function updateEbook(Request $request, $materi_id, $ebook_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $ebook = Ebook::where('materi_id', $materi_id)->where('ebook_id', $ebook_id)->firstOrFail();

        $request->validate([
            'judul' => 'required|string|max:255',
            'path_file' => 'nullable|file|mimes:pdf|max:51200', // 50MB max for PDF
        ]);

        $data = [
            'judul' => $request->judul,
        ];

        // Handle file upload
        if ($request->hasFile('path_file')) {
            // Delete old file if it exists
            if ($ebook->path_file && Storage::disk('public')->exists($ebook->path_file)) {
                Storage::disk('public')->delete($ebook->path_file);
            }

            $file = $request->file('path_file');
            $filename = time().'_ebook_'.preg_replace('/[^a-zA-Z0-9\._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('ebooks', $filename, 'public');
            $data['path_file'] = $path;
        }

        $ebook->update($data);

        return redirect()->route('admin.ebook', $materi_id)
            ->with('success', 'E-book berhasil diperbarui!');
    }

    /**
     * Remove the specified ebook
     */
    public function destroyEbook($ebook_id)
    {
        $ebook = Ebook::where('ebook_id', $ebook_id)->firstOrFail();
        $materi_id = $ebook->materi_id;

        // Delete associated file
        if ($ebook->path_file && Storage::disk('public')->exists($ebook->path_file)) {
            Storage::disk('public')->delete($ebook->path_file);
        }

        $ebook->delete();

        return redirect()->route('admin.ebook', $materi_id)
            ->with('success', 'E-book berhasil dihapus!');
    }
    
    // ========== TUGAS MANAGEMENT ==========

    /**
     * Display tugas index page
     */
    public function tugas($materi_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $tugas = $materi->tugas()->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.tugas.index', compact('materi', 'tugas'));
    }

    /**
     * Show form to create new tugas
     */
    public function createTugas($materi_id)
    {
        $materi = Materi::findOrFail($materi_id);
        
        return view('admin.tugas.create', compact('materi'));
    }

    /**
     * Store new tugas
     */
    public function storeTugas(Request $request, $materi_id)
    {
        $materi = Materi::findOrFail($materi_id);
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $tugasData = [
            'materi_id' => $materi_id,
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
        ];
        
        // Handle image upload if provided
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('tugas-images', 'public');
            $tugasData['gambar'] = $path;
        }
        
        $tugas = \App\Models\Tugas::create($tugasData);
        
        return redirect()->route('admin.tugas', $materi_id)
            ->with('success', 'Tugas berhasil ditambahkan');
    }

    /**
     * Show form to edit tugas
     */
    public function editTugas($materi_id, $tugas_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $tugas = \App\Models\Tugas::where('materi_id', $materi_id)
            ->where('tugas_id', $tugas_id)
            ->firstOrFail();
            
        return view('admin.tugas.edit', compact('materi', 'tugas'));
    }

    /**
     * Update tugas
     */
    public function updateTugas(Request $request, $materi_id, $tugas_id)
    {
        $tugas = \App\Models\Tugas::where('materi_id', $materi_id)
            ->where('tugas_id', $tugas_id)
            ->firstOrFail();
            
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $tugas->judul = $validated['judul'];
        $tugas->deskripsi = $validated['deskripsi'];
        
        // Handle image upload if provided
        if ($request->hasFile('gambar')) {
            // Delete previous image if exists
            if ($tugas->gambar) {
                Storage::disk('public')->delete($tugas->gambar);
            }
            
            $path = $request->file('gambar')->store('tugas-images', 'public');
            $tugas->gambar = $path;
        }
        
        $tugas->save();
        
        return redirect()->route('admin.tugas', $materi_id)
            ->with('success', 'Tugas berhasil diperbarui');
    }

    /**
     * Delete tugas
     */
    public function destroyTugas($materi_id, $tugas_id)
    {
        $tugas = \App\Models\Tugas::where('materi_id', $materi_id)
            ->where('tugas_id', $tugas_id)
            ->firstOrFail();
            
        // Delete image if exists
        if ($tugas->gambar) {
            Storage::disk('public')->delete($tugas->gambar);
        }
        
        $tugas->delete();
        
        return redirect()->route('admin.tugas', $materi_id)
            ->with('success', 'Tugas berhasil dihapus');
    }
}
