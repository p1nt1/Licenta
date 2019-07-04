<?php

namespace Colorize\Http\Controllers;

use Colorize\Client;
use Colorize\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function get(){
        return Post::paginate(5);
    }

    public function getAll(){
        return Post::all();
    }

    public function create(Request $request){

        $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:100',
            'size' => 'required|integer',
            'price' => 'required|integer'
        ]);

        $obj = new Post([
            'title' => $request->title,
            'description' => $request->description,
            'size' => $request->size,
            'picture' => 'public/images/icon.jpeg' ,
            'price' => $request->price,
            'user_id' => auth()->id(),
            'status' => 0
        ]);

        $obj->save();

        $image = $request->picture;
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        Storage::put('/public/picture/image_'. $obj->id . '.jpeg', base64_decode($image));

        $obj->update([
            'picture' => '/storage/picture/image_'. $obj->id . '.jpeg'
        ]);

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

    public function status(Request $request){
        $post = Post::find($request['id']);
        $post->status = $request['status'];
        $post->save();

        return response()->json('Status changed!');
    }
}
