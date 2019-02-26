<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use DB;
use Hash;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Validator;

class ResetController extends Controller
{
    use ResetsPasswords;
    /**
     * Reset Token Rules
     *
     * @var array
     */
    protected $rules = [
        'email' => ['required', 'string', 'email', 'max:255'],
        'password' => ['required', 'string', 'min:8'],
        'password_confirmation' => ['required', 'same:password'],
        'token' => ['required', 'string']
    ];
    /**
     * Undocumented function
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function reset(Request $request) : JsonResponse
    {
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');
        $validator = Validator::make($credentials, $this->rules);

        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'error'=> $validator->messages()
            ]);
        }

        $reset = $this->broker()->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        if ($reset == Password::INVALID_USER) {
            return response()->json([
                'success'=> false,
                'error'=> 'Incorrect user credentials.'
            ]);
        }

        if ($reset == Password::INVALID_PASSWORD) {
            return response()->json([
                'success'=> false,
                'error'=> 'Incorrect user credentials.'
            ]);
        }

        if ($reset == Password::INVALID_TOKEN) {
            return response()->json([
                'success'=> false,
                'error'=> 'Invalid token.'
            ]);
        }

        return response()->json([
            'success'=> true,
            'error'=> 'Password updated.'
        ]);
    }

    /**
     * Verify the recovery token and return the user
     *
     * @param string $token
     * @return App\User|false
     */
    protected function getUserByToken(string $token)
    {
        $check = DB::table('password_resets')
            ->where('token', $token)
            ->first();
        
        dd($check);

        if (is_null($check)) {
            return false;
        }

        return User::where('email', $check->email)->first();
    }
}
