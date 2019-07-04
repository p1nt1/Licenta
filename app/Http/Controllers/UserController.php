<?php

namespace Colorize\Http\Controllers;

use Colorize\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function get(){
        return response()->json(User::all());

    }

    public function find($id){
        return response()->json(User::where('id', '=', $id)->get());
    }

    public function delete($id){
        $user = User::where('id', $id)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found!'
            ], 404);
        }

        $user->delete();

        return response()->json(
            ['message'=>'User deleted']
        );
    }

    public function edit($id, Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|regex:/(07)[0-9]{8}/',
            'role' => 'required|between:1,2',
            'city' => 'required|string|max:20'
        ]);

        $user = User::where('id', $id)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found!'
            ], 404);
        }

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'role' => $request->role,
            'city' => $request->city
        ]);

        return response()->json(
            ['message' => 'User updated!']
        );
    }
}
