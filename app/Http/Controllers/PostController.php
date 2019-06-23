<?php

namespace Colorize\Http\Controllers;

use Colorize\Client;
use Colorize\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function get(){
        return Post::paginate(5);
    }

    public function getAll(){
        return Post::all();
    }

    public function create(Request $request){


        $obj = new Post([
            'picture' => '',
            'price' => $request->price,
            'title' => $request->title,
            'user_id' => auth()->id(),
            'size' => $request->size,
            'status' => 0
        ]);

        $obj->save();

//        $image = $request->picture;
//        $image = str_replace('data:image/png;base64,', '', $image);
//        $image = str_replace(' ', '+', $image);
//
//        Storage::put('/public/Posts/'. $obj->id .'.png', base64_decode($image));
//
//        $obj->update([
//            'picture' => '/storage/Posts/' . $obj->id . '.png'
//        ]);
//
//        $obj->save();

        return response()->json([
            'message' => 'Post added!',
            'id' => $obj->id
        ]);
    }

    public function find($id){
        return response()->json(Post::where('id', '=', $id)->get());
    }

    public function delete($id){
        $user = Post::where('id', $id)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Post not found!'
            ], 404);
        }

        $user->delete();

        return response()->json(
            ['message'=>'Post deleted']
        );
    }

    public function edit($id, Request $request){
        $request->validate([
            'picture' => 'required|string'
        ]);

        $user = Post::where('id', $id)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Post not found!'
            ], 404);
        }

        $image = $request->picture;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);

        Storage::put('/public/Posts/'. $user->id .'/avatar.png', base64_decode($image));

        return response()->json(
            ['message' => 'Post updated!']
        );
    }

    public function search(Request $request){
        return response()->json(Post::where('title', 'like', '%' . $request->word . '%')->get());
    }
}
