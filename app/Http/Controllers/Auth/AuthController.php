<?php

namespace Colorize\Http\Controllers\Auth;

use Colorize\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Avatar;
use Colorize\Notifications\SignupActivate;
use Colorize\Notifications\SignupActivated;
use Colorize\User;

class AuthController extends Controller
{
    /**
     * Create user deactivate and send notification to activate account user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] phone
     * @param  [int] idType
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'phone' => 'required|string|regex:/(07)[0-9]{8}/',
            'role' => 'required|integer|between:1,2'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'activation_token' => str_random(60)
        ]);

        $user->save();

        $avatar = new Avatar();
        Storage::put('public/users/'.$user->id.'/avatar.png', $avatar->create($user->name)->getImageObject()->encode('png'));

        $user->update([
            'avatar' => '/storage/users/'.$user->id.'/avatar.png'
        ]);

        $user->notify(new SignupActivate());

        return response()->json([
            'message' => __('auth.signup_success')
        ], 201);
    }

    /**
     * Confirm your account user (Activate)
     *
     * @param  [type] $token
     * @return [string] message
     * @return [obj] user
     */
    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return response()->json([
                'message' => __('auth.token_invalid')
            ], 404);
        }

        $user->active = true;
        $user->activation_token = '';
        $user->save();

        $user->notify(new SignupActivated($user));

        return $user;
    }

    public function getUserByActivationToken($token){
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return abort(404);
        }

        return response()->json($user);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'string',
            'remember_me' => 'boolean'
        ]);

        $userActive = User::where('email', $request->email)->first();
        if($userActive->active == 0){
            return response()->json(
                ['message' => 'Please verify your email address!'], 401
            );
        }

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials))
            return response()->json([
                'message' =>'Wrong email or password!'
            ], 401);

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember == true)
            $token->expires_at = Carbon::now()->addWeeks(1);
        else
            $token->expires_at = Carbon::now()->addHours(24);

        $token->save();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'role' => $user->role
                ],
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => __('auth.logout_success')
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
