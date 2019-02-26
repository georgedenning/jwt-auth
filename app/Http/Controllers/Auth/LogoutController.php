<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    /**
     * Invalidate the token
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function logout(Request $request) : JsonResponse
    {
        $token = $request->bearerToken();

        try {
            auth('api')->invalidate($token);
            return response()->json([
                'success' => true,
                'message'=> 'You have successfully logged out.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to logout, please try again.'
            ], 500);
        }
    }
}
