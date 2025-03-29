<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UserModel; 
use App\Models\LevelModel;


class AuthController extends Controller
{
        public function login()
    {
        if (Auth::check()) { // jika sudah login, maka redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');
            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }
        return redirect('login');
    }

    public function register()
    {
        return view('auth.register');
    }


    public function postRegister(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|unique:m_user,username|min:4|max:20',
            'password' => 'required|string|min:6|max:20|confirmed',
            'nama' => 'required|string|max:100',
        ]);

        $customerLevel = LevelModel::where('level_nama', 'Customer')->first();
        $user = UserModel::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nama' => $request->nama,
            'level_id' => $customerLevel->level_id,
        ]);

        Auth::login($user);

        return response()->json([
            'status' => true,
            'message' => 'Registration successful!',
            'redirect' => url('/'),
        ]);;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

}
