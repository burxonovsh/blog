<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateRequest;
use App\Mail\SendSmsToMail;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function index(){
        $posts = Post::all();
        return view('welcome', compact('posts'));
    }
    public function registerForm(){
        return view("blog.auth.register");
    }
    public function Register(RegisterRequest $request){
        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->verification_token = uniqid();
        $user->password = bcrypt($request->password);
        $user->save();

        $uploadedAvatar = $this->uploadAvatar($request->file('avatar'));
        $user->image()->create([
            'image_path'=> $uploadedAvatar,
        ]);
        Mail::to($user->email)->send(new SendSmsToMail($user));
        return redirect()->route('loginForm');

    }

    public function loginForm(){
        if(!Auth::check()){

            return view("blog.auth.login");
        }
        return redirect()->back();
    }

    public function login(LoginRequest $request){
 
    $user = User::where('email', $request->input('email'))->first();
    if (!$user || !Hash::check($request->input('password'), $user->password )||$user->email_verified_at == null) {
        return redirect()->route('loginForm');
    }
    if (Auth::attempt($request->only('email', 'password'))) {
        $request->session()->regenerate();
        return redirect()->route('home');
    }
    return redirect()->route('loginForm');
}
    public function profile(){
        $posts = Auth::user()->posts()->orderBy("created_at","desc")->paginate(4);
        return view("blog.auth.profile", compact("posts"));
    }
    public function editProfile(){
        return view("blog.auth.edit");
    }
    public function update(UpdateRequest $request){
        if(Auth::check()){
            $id = Auth::id();
        }
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;

        if(!empty($request->old_password)){
            if(!Hash::check($request->old_password, $user->password)){
                    return redirect()->back()->with("error", "This password is incorrect");
                }
            $user->password = bcrypt($request->new_password);
        }
        $user->save();
        if($request->hasFile("avatar")){
            if($user->image->image_path){
                $this->deleteAvatar($user->image->image_path);
            }
            $uploadedAvatar = $this->uploadAvatar($request->file('avatar'));
            $user->image()->update([
                'image_path'=> $uploadedAvatar
            ]);
        }
        return redirect()->route('my.profile');

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
    public function emailVerify(Request $request){
        $user = User::where('verification_token', $request->token)->first();
        if(!$user || $user->verification_token !== $request->token){
            abort(404);
        }

        $user->email_verified_at = now();
        $user->save();
        return redirect()->route('loginForm');
    }

}