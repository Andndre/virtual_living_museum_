<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SitusPeninggalan;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SitusController extends Controller
{
    public function index()
    {
        $situs = SitusPeninggalan::with(['materi', 'user', 'virtualMuseum', 'virtualMuseumObject'])
            ->orderBy('situs_id', 'desc')
            ->paginate(15);

        return view('admin.situs.index', compact('situs'));
    }

    public function show($id)
    {
        $situs = SitusPeninggalan::with(['materi', 'user', 'virtualMuseum.virtualMuseumObjects', 'virtualMuseumObject'])
            ->findOrFail($id);

        return view('admin.situs.show', compact('situs'));
    }

    public function create()
    {
        $materis = Materi::orderBy('urutan')->get();
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.situs.create', compact('materis', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'deskripsi' => 'required|string',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'masa' => 'required|string|max:100',
            'materi_id' => 'required|exists:materi,materi_id',
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            $situs = SitusPeninggalan::create($validated);

            DB::commit();

            return redirect()->route('admin.situs.show', $situs->situs_id)
                ->with('success', 'Situs peninggalan berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating situs: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan situs peninggalan.');
        }
    }

    public function edit($id)
    {
        $situs = SitusPeninggalan::findOrFail($id);
        $materis = Materi::orderBy('urutan')->get();
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.situs.edit', compact('situs', 'materis', 'users'));
    }

    public function update(Request $request, $id)
    {
        $situs = SitusPeninggalan::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'deskripsi' => 'required|string',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'masa' => 'required|string|max:100',
            'materi_id' => 'required|exists:materi,materi_id',
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            $situs->update($validated);

            DB::commit();

            return redirect()->route('admin.situs.show', $situs->situs_id)
                ->with('success', 'Situs peninggalan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating situs: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui situs peninggalan.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $situs = SitusPeninggalan::findOrFail($id);
            
            // Check if situs has virtual museum objects
            if ($situs->virtualMuseumObject()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus situs yang masih memiliki objek virtual museum.');
            }

            $situs->delete();

            DB::commit();

            return redirect()->route('admin.situs')
                ->with('success', 'Situs peninggalan berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting situs: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus situs peninggalan.');
        }
    }
}
