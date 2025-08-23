<?php

namespace App\Http\Controllers;

use App\Models\VirtualMuseumObject;

class ArMarkerCameraController extends Controller
{
    /**
     * Display the AR Marker camera view.
     */
    public function index()
    {
        // get all ar objects
        $arObjects = VirtualMuseumObject::all();

        return view('guest.ar-camera', compact('arObjects'));
    }
}
