<?php

namespace App\Http\Controllers;

use App\Models\ArMarker;

class ArMarkerCameraController extends Controller
{
    /**
     * Display the AR Marker camera view.
     */
    public function index()
    {
        $arMarkers = ArMarker::with(['virtualMuseumObjects' => function ($query) {
            $query->whereNotNull('path_obj')->orderBy('object_id');
        }])
            ->whereNotNull('path_patt')
            ->orderBy('marker_id')
            ->get();

        return view('guest.ar-camera', compact('arMarkers'));
    }
}
