<?php

namespace Colorize\Http\Controllers;

use Colorize\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function get(){
        return Client::all();
    }

    public function create(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:clients',
            'picture' => 'required'
        ]);

        $client = new Client([
            'name' => $request->name,
            'email' => $request->email,
            'picture' => 'null'
        ]);

        $client->save();

        $image = $request->picture;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);

        Storage::put('/public/clients/'. $client->id .'/avatar.png', base64_decode($image));

        $client->update([
            'picture' => '/storage/clients/' . $client->id . '/avatar.png'
        ]);

        $client->save();

        return response()->json([
            'message' => 'Client added!',
            'id' => $client->id
        ]);
    }

    public function find($id){
        return response()->json(Client::where('id', '=', $id)->get());
    }

    public function delete($id){
        $user = Client::where('id', $id)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Client not found!'
            ], 404);
        }

        $user->delete();

        return response()->json(
            ['message'=>'Client deleted']
        );
    }

    public function edit($id, Request $request){
        $request->validate([
            'picture' => 'required|string'
        ]);

        $user = Client::where('id', $id)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Client not found!'
            ], 404);
        }

        $image = $request->picture;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);

        Storage::put('/public/clients/'. $user->id .'/avatar.png', base64_decode($image));

        return response()->json(
            ['message' => 'Client updated!']
        );
    }

    public function search(Request $request){
        return response()->json(Client::where('name', 'like', '%' . $request->word . '%')->get());
    }
}
