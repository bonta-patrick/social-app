<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user) {

        if($user->id == auth()->user()->id) {
            return back()->with('FA1unt_ap', 'You cannot follow yourself!');
        }

        $checkAlreadyFollowed = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();

        if($checkAlreadyFollowed) {
            return back()->with('FA1unt_ap', 'You are already following ' . $user->username);
        }

        $newFollow = new Follow;

        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();

        return back()->with('SuccesSTAT', 'You are now following ' . $user->username);
    }

    public function removeFollow(User $user) {
        Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->delete();
        return back()->with('SuccesSTAT', 'You stopped following ' . $user->username);
    }
}
