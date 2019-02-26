<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Controllers\Controller;
use App\User;
use Mail;
use Validator;

class RecoverController extends Controller
{
    use SendsPasswordResetEmails;
    /**
     * Rules
     *
     * @var array
     */
    protected $rules = [
        'email' => ['required', 'string', 'email', 'max:255']
    ];
    /**
     * Recover a password
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function recover(Request $request) : JsonResponse
    {
        $credentials = $request->only('email');
        $validator = Validator::make($credentials, $this->rules);

        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'error'=> $validator->messages()
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'error' => ['email' => 'Email address not found.']
            ], 401);
        }

        if (! $this->sendRecoveryEmail($user)) {
            return response()->json([
                'success' => false,
                'error' => 'Recovery email could not be sent, please try later.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Check your email for a reset link.'
        ]);
    }

    /**
     * Generate the reset token and send the recovery email
     *
     * @param User $user
     * @return boolean
     */
    protected function sendRecoveryEmail(User $user)
    {
        // Generate recovery token
        $token = $this->broker()->createToken($user);

        $name = $user->name;
        $email = $user->email;
        $subject = 'Reset your password';

        Mail::send('email.recovery', [
            'name' => $name,
            'token' => $token
        ], function ($mail) use ($email, $name, $subject) {
            $mail->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'));
            $mail->to($email, $name);
            $mail->subject($subject);
        });

        return count(Mail::failures()) < 1;
    }
}
