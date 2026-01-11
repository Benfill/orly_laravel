<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class DocsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/health",
     *     tags={"System"},
     *     summary="Health check",
     *     @OA\Response(response=200, description="API is running")
     * )
     */
    public function health()
    {
        return response()->json(['status' => 'ok']);
    }
}
