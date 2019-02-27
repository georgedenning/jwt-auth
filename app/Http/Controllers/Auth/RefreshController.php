<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class RefreshController extends Controller
{
    public function refresh(Request $request) : JsonResponse
    {
        try {
            $token = auth('api')->refresh($request->bearerToken());
        } catch (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid token.'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'expires' => auth('api')->factory()->getTTL() * 60
            ]
        ], 200);
    }
}
