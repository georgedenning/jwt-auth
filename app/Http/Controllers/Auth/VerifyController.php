<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class VerifyController extends Controller
{
    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'verification' => ['required', 'string', 'size:30']
    ];
    
    /**
     * Verify a code
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function verify(Request $request) : JsonResponse
    {
        $verification = $request->only('verification');
        $validator = Validator::make($verification, $this->rules);

        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'error'=> $validator->messages()
            ]);
        }

        $user = $this->checkVerification($verification);

        if (! $user) {
            return response()->json([
                'success'=> false,
                'error'=> 'Invalid verification code.'
            ]);
        }

        if ($user->is_verified == 1) {
            return response()->json([
                'success' => true,
                'message' => 'Account already verified.'
            ]);
        }

        $this->confirmVerification($user, $verification);

        return response()->json([
            'success'=> true,
            'message'=> 'You have successfully verified your email address.'
        ]);
    }
    /**
     * Verify a token and return the correct user.
     *
     * @param array $verification
     * @return App\User|false
     */
    protected function checkVerification(array $verification)
    {
        $check = DB::table('user_verifications')
            ->where('token', $verification)
            ->first();

        if (is_null($check)) {
            return false;
        }

        return User::find($check->user_id);
    }

    /**
     * Update the user to confirm the verification.
     *
     * @param \App\User $user
     * @param array $verification
     */
    protected function confirmVerification(User $user, array $verification)
    {
        $user->is_verified = 1;
        $user->email_verified_at = Carbon::now();
        $user->save();
        
        DB::table('user_verifications')
            ->where('token', $verification)->delete();
    }
}
