<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function signup() {
        // return view('auth/signup');
    }

    public function registration(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|unique:App\Models\User|email',
            'password'=>'required|min:6'
        ]);
        $response=[
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password
        ];
        // return response()->json($response);
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make(request('password')),
            'role'=>"reader",
        ]);

        $token = $user->createToken('MyAppToken')->plainTextToken;
        $user->remember_token = $token;
        $user->save();
        // return redirect()->route('login');
        return response()->json($user);
    }

    public function login() {
        // return view('auth.signin');
    }

    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:6',
        ]);

        if(Auth::attempt($credentials, $request->remember)) {
            // $request->session()->regenerate();
            $token = $request->user()->createToken('MyAppToken')->plainTextToken;
            return response()->json($token);
        };

        // return back()->withErrors([
        //     'email'=>'The provided credentials do not match our records'
        // ])->onlyInput('email');
        // return response([
        //     'email'=>'The provided credentials do not match our records'
        // ]);
    }

    public function logout(Request $request) {
        Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        return response('Logout');
    }
}
