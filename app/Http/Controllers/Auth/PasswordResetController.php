<?php

namespace Colorize\Http\Controllers\Auth;

use Carbon\Carbon;
use Colorize\Http\Controllers\Controller;
use Colorize\Notifications\PasswordResetRequest;
use Colorize\Notifications\PasswordResetSuccess;
use Colorize\PasswordReset;
use Colorize\User;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user)
            return response()->json([
                'message' => ''
            ], 404);

        $passwordReset = PasswordReset::updateOrCreate(['email' => $user->email], [
            'email' => $user->email,
            'token' => str_random(60)
        ]);

        if ($user && $passwordReset)
            $user->notify(new PasswordResetRequest($passwordReset->token));

        return response()->json([
            'message' => __('passwords.sent')
        ]);
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset)
            return response()->json([
                'message' => __('passwords.token')
            ], 404);

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => __('passwords.token')
            ], 404);
        }

        return response()->json($passwordReset);
    }

    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        if (!$passwordReset)
            return response()->json([
                'message' => __('passwords.token')
            ], 404);

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user)
            return response()->json([
                'message' => __('passwords.user')
            ], 404);

        $user->password = bcrypt($request->password);
        $user->save();

        $passwordReset->delete();

        $user->notify(new PasswordResetSuccess($passwordReset));

        return response()->json([
            'message' => 'Password changed. You can log in!'
        ]);
    }

    public function changePassword(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed|min:6|max:255',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user)
            return response()->json([
                'User not found!'
            ], 404);

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['message' => 'Password changed with success!']);
    }
}
