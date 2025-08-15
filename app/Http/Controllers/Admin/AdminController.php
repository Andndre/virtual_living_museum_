<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Materi;
use App\Models\SitusPeninggalan;
use App\Models\LaporanPeninggalan;
use App\Models\KritikSaran;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $users = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * Display materi management
     */
    public function materi()
    {
        $materi = Materi::orderBy('urutan', 'asc')->get();
        return view('admin.materi', compact('materi'));
    }

    /**
     * Display situs management
     */
    public function situs()
    {
        $situs = SitusPeninggalan::with(['user', 'materi'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.situs', compact('situs'));
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
     * Display feedback management
     */
    public function feedback()
    {
        $feedback = KritikSaran::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.feedback', compact('feedback'));
    }
}
