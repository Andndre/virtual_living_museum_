<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VirtualMuseum;
use Illuminate\Http\JsonResponse;

class AnnotationController extends Controller
{
    public function index(VirtualMuseum $museum): JsonResponse
    {
        $annotations = $museum->annotations()
            ->where('is_visible', true)
            ->orderBy('display_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $annotations,
        ]);
    }
}
