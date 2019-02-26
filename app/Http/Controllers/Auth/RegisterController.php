<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Hash;
use Mail;
use Validator;

class RegisterController extends Controller
{
    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8'],
    ];

    /**
     * User Registration
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function register(Request $request) : JsonResponse
    {
        $credentials = $request->only('name', 'email', 'password');
        $validator = Validator::make($credentials, $this->rules);

        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'error'=> $validator->messages()
            ]);
        }

        $user = User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password'])
        ]);

        if (! $this->sendVerificationEmail($user)) {
            return response()->json([
                'success' => false,
                'error' => 'Verification email could not be sent, please try later.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => ['user' => $user],
            'message' => 'Check your email to complete your registration.'
        ]);
    }

    /**
     * Save the verification code and send verification email
     *
     * @param App\User $user
     * @return boolean
     */
    protected function sendVerificationEmail(User $user)
    {
        // Generate verification code
        $verification_code = str_random(30);

        DB::table('user_verifications')->insert([
            'user_id' => $user->id,
            'token' => $verification_code
        ]);

        $name = $user->name;
        $email = $user->email;
        $subject = 'Please verify your email address';

        Mail::send('email.verify', [
            'name' => $name,
            'verification_code' => $verification_code
        ], function ($mail) use ($email, $name, $subject) {
            $mail->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'));
            $mail->to($email, $name);
            $mail->subject($subject);
        });

        return count(Mail::failures()) < 1;
    }
}
