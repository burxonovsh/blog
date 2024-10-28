<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Mail\SendSmsToMail;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function registerForm(){
        return view("blog.auth.register");
    }
    public function Register(RegisterRequest $request){
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }
        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->verification_token = uniqid();
        $user->password = bcrypt($request->password);
        $user->save();

        // Mail::to($user->email)->send(new SendSmsToMail($user));
        return redirect()->route('home');

    }
    public function loginForm(){
        if(!Auth::check()){

            return view("blog.auth.login");
        }
        return redirect()->back();
    }

    public function Login(LoginRequest $request){
        $credentials = $request->all();
        $user = User::where('email', $request->input('email'))->first();

        if(!Hash::check($request->password, $user->password)){
            return redirect()->route('blog.auth.login');
        }
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->route('blog.post.index');
        }
        else{
            return false;
        }
       
    }
    public function profile(){
        $posts = Auth::user()->posts()->orderBy("created_at","desc")->paginate(4);
        return view("blog.auth.profile", compact("posts"));
    }
    public function editProfile(){
        return view("blog.auth.edit");
    }
    public function update(UpdateRequest $request){
        $user = User::find($id);
        if (!empty($user->avatar)) {
            $oldAvatarPath = storage_path('app/public/' . $user->avatar);
            if (file_exists($oldAvatarPath)) {
                    unlink($oldAvatarPath);
                }
            }
        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        if ($request->hasFile('avatar')) {
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }
        $user->save();
        return redirect()->route('blog.auth.profile');

    }
    public function logout(){
        Auth::logout();
        return redirect()->route('loginForm');
    }
    public function uploadAvatar($avatar){
        $avatarPath = time() . "." . $avatar->getClientOriginalExtension();
        $uploadedAvatar = $avatar->storeAs("uploads", $avatarPath, "public");
        return $uploadedAvatar;
    }

    public function deleteAvatar($avatar){
        @unlink(storage_path('app/public/' . $avatar));
        return;
    }
}
