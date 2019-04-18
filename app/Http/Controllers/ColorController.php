<?php

namespace Colorize\Http\Controllers;

use Colorize\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function get(){
        return Color::paginate(5);
    }

    public function getAll(){
        return Color::all();
    }

    public function create(Request $request){
        $request->validate([
            'name_color' => 'required|string',
            'color_hex' => 'required|string|unique:colors'
        ]);

        $color = new Color([
            'name_color' => $request->name_color,
            'color_hex' => $request->color_hex
        ]);

        $color->save();

        return response()->json([
            'message' => 'Color added!'
        ]);
    }

    public function find($id){
        return response()->json(Color::where('id', '=', $id)->get());
    }

    public function delete($id){
        $user = Color::where('id', $id)->first();

        if (!$user) {
            return response()->json([
                'message' =>' Color not found!'
            ], 404);
        }

        $user->delete();

        return response()->json(
            ['message'=>'Color deleted']
        );
    }

    public function edit($id, Request $request){
        $request->validate([
            'name_color' => 'required|string',
            'color_hex' => 'required|string'
        ]);

        $user = Color::where('id', $id)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Color not found!'
            ], 404);
        }

        $user->update([
            'name_color' => $request->name_color,
            'color_hex' => $request->color_hex
        ]);

        return response()->json(
            ['message' => 'Color updated!']
        );
    }
}
