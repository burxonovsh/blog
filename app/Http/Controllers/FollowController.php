<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\FollowNotification;



class FollowController extends Controller
{
    public function follow($id){
        $user = User::findOrFail($id);
        auth()->user()->following()->attach($user->id);
        $user->notify(new FollowNotification(auth()->user()));
        return redirect()->back();
    }
    public function unfollow($id){
        $user = User::findOrFail($id);
        auth()->user()->following()->detach($user->id);
        return redirect()->back();
    }
}
