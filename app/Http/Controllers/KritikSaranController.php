<?php

namespace App\Http\Controllers;

use App\Models\KritikSaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KritikSaranController extends Controller
{
    /**
     * Display the criticism and suggestions page
     */
    public function index()
    {
        // Get criticism and suggestions for the current user
        $kritikSaran = KritikSaran::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('guest.kritik-saran.index', compact('kritikSaran'));
    }

    /**
     * Store a new criticism or suggestion
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'pesan' => 'required|string|max:1000',
        ]);

        // Create a new criticism or suggestion
        KritikSaran::create([
            'user_id' => Auth::id(),
            'pesan' => $request->pesan,
            'created_at' => now(),
        ]);

        return redirect()->route('guest.kritik-saran')
            ->with('success', 'Kritik dan saran berhasil dikirim. Terima kasih atas masukan Anda!');
    }
}
