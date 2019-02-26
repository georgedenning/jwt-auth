<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\User;
use Validator;

class LoginController extends Controller
{
    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'email' => ['required', 'string', 'email', 'max:255'],
        'password' => ['required', 'string', 'min:6'],
    ];

    /**
     * Login and return the user with token.
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function login(Request $request) : JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($credentials, $this->rules);
        
        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'error'=> $validator->messages()
            ], 401);
        }

        $credentials['is_verified'] = 1;

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'error' => 'We can\'t find your account or your password is incorrect, please try again.'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => User::whereEmail($credentials['email'])->first(),
                'token' => $token,
                'expires' => auth('api')->factory()->getTTL() * 60
            ]
        ], 200);
    }
}
