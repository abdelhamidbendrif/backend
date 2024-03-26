<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function register(Request $req) {
        $user = new User;
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $user->password = Hash::make($req->input('password'));
        $user->save();

        return $user;
    }
    
    function login(Request $req) {
        $user = User::where('email', $req->email)->first();
        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json(['error' => 'Email or password is incorrect'], 401);
        }
        return response()->json($user, 200);
    }

    function getUser($id) {
        return User::find($id);
    }

    function checkEmail(Request $req) {
        $emailExists = User::where('email', $req->email)->exists();
        return response()->json(['exists' => $emailExists]);
    }

    function checkUsername(Request $req) {
        $usernameExists = User::where('name', $req->name)->exists();
        return response()->json(['exists' => $usernameExists]);
    }

    function searchUser($key) {
        return User::where('name', 'like', "%$key%")->get();
    }

    function uploadAvatar($id, Request $req) {
        $req->validate([
            'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = User::find($id);

        if ($req->hasFile('file')) {
            $user->avatar = $req->file('file')->store('products');
        }
        $user->save();
        return $user;
    }

  
    function update($id ,Request $req) {

        $user = User::find($id);
        if ($req->has('name')) {
            $user->name = $req->input('name');
        }
        if ($req->has('email')) {
            $user->email = $req->input('email');
        }
        if ($req->has('phone_number')) {
            $user->phone_number = $req->input('phone_number');
        }
        if ($req->has('address')) {
            $user->address = $req->input('address');
        }
       
        $user->save();
        return $user;

    }
}
