<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Get dynamic greeting based on current time
     */
    private function getGreeting()
    {
        $hour = Carbon::now()->hour;
        
        if ($hour >= 5 && $hour < 12) {
            return __('app.good_morning');
        } elseif ($hour >= 12 && $hour < 17) {
            return __('app.good_afternoon');
        } elseif ($hour >= 17 && $hour < 21) {
            return __('app.good_evening');
        } else {
            return __('app.good_night');
        }
    }

    public function index(Request $request)
    {
        $greeting = $this->getGreeting();
        return view('guest.home', compact('greeting'));
    }

    public function panduan(Request $request)
    {
        return view('guest.panduan');
    }

    public function pengaturan(Request $request)
    {
        return view('guest.pengaturan');
    }

    public function pengembang(Request $request)
    {
        return view('guest.pengembang');
    }

    public function arMarker(Request $request)
    {
        return view('guest.ar-marker');
    }
}
