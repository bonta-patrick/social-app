<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function storeAvatar(Request $request) {
        $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);

        $user = auth()->user();
        $filename = $user->id . '-' . uniqid() . '.jpg';
        
        $imgData = Image::make($request->file('avatar'))->fit(120)->encode('jpg');
        Storage::put('public/avatars/' . $filename, $imgData);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if($oldAvatar != "/fallback-avatar.jpg") {
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
        }

        return redirect("/profile/" . $user->username)->with('SuccesSTAT', 'You succesfully uploaded the avatar image!');
    }
 
    public function showManageAvatarPage() {
        return view('avatar-form');
    }

    private function getSharedData($user) {
        $currentlyFollowing = 0;

        if(auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }

        View::share('sharedData', ['isFollowingUser' => $currentlyFollowing, 'avatar' => $user->avatar, 'username' => $user->username, 'postCount' => $user->posts()->count(), 'followerCount' => $user->followers()->count(), 'followCount' => $user->followingTheseUsers()->count()]);
    }

    public function showUserProfile(User $user) {
        $this->getSharedData($user);
        return view('profile-posts', ['posts' => $user->posts()->latest()->get()]);
    }

    public function profileFollowers(User $user) {
        $this->getSharedData($user);
        return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);
    }

    public function profileFollowing(User $user) {
        $this->getSharedData($user);
        return view('profile-following', ['following' => $user->followingTheseUsers()->latest()->get()]);
    }

    public function logout() {
        auth()->logout();
        return redirect('/')->with('SuccessfulLO', 'You have successfully logged out!');
    }

    public function showCorrectHomepage() {
        if(auth()->check()) {
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(4)]);
        } else {
            return view('homepage');
        }
    }

    public function login(Request $request) {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate(); //we create a cookie in a session so that the browser can tell if we had logged in the past
            return redirect('/')->with('SuccesSTAT', 'You have succesfully logged in!');
        } else {
            return redirect('/')->with('FA1unt_ap', 'Incorrect credentials!');
        }
    }


    public function register(Request $request) {
        $incomingFields = $request->validate([
            'username' => ['required', 'min: 3', 'max: 20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min: 8', 'confirmed']
        ]);

        $user = User::create($incomingFields);
        auth()->login($user);
        $profilePost = new Post();
        $profilePost->title = auth()->user()->username;
        $profilePost->body = 'Description (Edit it) ... This is an unit that serves as your profile info available to search! If you delete this post, you can anytime make another profile info post!';
        $profilePost->user_id = auth()->user()->id;
        $profilePost->save();

        
        return redirect('/')->with('SuccesSTAT', 'You succesfully registered to UnitApp!');
    }
}
