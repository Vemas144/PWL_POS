<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Profil User',
            'list'  => ['Home', 'Profil']
        ];

        $page = (object) [
            'title' => 'Profil user yang sedang login'
        ];

        $activeMenu = 'profile'; // untuk menandai menu aktif di sidebar

        $user = Auth::user();

        return view('profile.index', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'user'       => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update_photo(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::exists('public/foto/' . $user->foto)) {
                Storage::delete('public/foto/' . $user->foto);
            }

            // Simpan foto baru
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/foto', $filename);

            // Simpan nama file ke kolom user
            $user= UserModel::find($user->user_id);
            $user->foto = $filename;
            $user->save();
        }
        

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui.');
    }

}
