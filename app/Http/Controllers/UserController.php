<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    public function index()
    {
        $users = UserModel::with('level')->get();
        return view('user', ['data' => $users]);

        //tambah data user dengan Elequent Model
    //      $data = [
    //         'level_id' => 2,
    //         'username' => 'Manager_tiga',
    //         'nama' => 'Manager 3',
    //         'password' => Hash::make('12345'),
    //    ];
    //     UserModel::create($data); 

        // $data = [
        //     'nama' => 'Pelanggan Pertama'
        // ];
        // UserModel::where('username', 'customer-1')->update($data);

        
        // $users = UserModel::findOr(20, ['username', 'nama'], function(){
        //     abort(404); 
        // }); 
        //$users = UserModel::where('username', 'manager9')->findOrFail(1);
        // $users = UserModel::where('level_id', 2)->count();
        // ($users);

        // $users = UserModel::firstOrNew(
        //     [
        //         'username' => 'manager33',
        //         'nama' => 'Manager Tiga Tiga',
        //         'password' => Hash::make('12345'),
        //         'level_id' => 2
        //     ],
        // );
        // $users->save();
        // return view('user', ['data' => $users]);

        // $users = UserModel::create(
        //     [
        //         'username' => 'manager55',
        //         'nama' => 'Manager55',
        //         'password' => Hash::make('12345'),
        //         'level_id' => 2,
        //     ]);

        //     $users->username = 'manager56';

        //     $users->isDirty(); // true
        //     $users->isDirty('username'); // true
        //     $users->isDirty('nama'); // false
        //     $users->isDirty(['nama', 'username']); // true

        //     $users->isClean(); // false
        //     $users->isClean('username'); //false
        //     $users->isClean('nama'); // true
        //     $users->isClean(['nama', 'username']); // false

        //     $users->save();

        //     $users->isDirty(); // false
        //     $users->isClean(); // true
        //     dd($users->isDirty());

        // $users = UserModel::create(
        //     [
        //         'username' => 'manager11',
        //         'nama' => 'Manager11',
        //         'password' => Hash::make('12345'),
        //         'level_id' => 2,
        //     ]);

        //     $users->username = 'manager12';

        //     $users->save();

        //     $users->wasChanged(); // true
        //     $users->wasChanged('username'); // true
        //     $users->wasChanged(['username', 'level_id']); // true
        //     $users->wasChanged('nama'); // false
        //     dd($users->wasChanged(['nama', 'username'])); // true

        //$users = UserModel::all();
        return view('user', ['data' => $users]);
    }

    public function tambah()
    {
        return view('user_tambah');
    }

    public function tambah_simpan(Request $request)
    {
    UserModel::create([
        'username' => $request->username,
        'nama' => $request->nama,
        'password' => Hash::make($request->password),
        'level_id' => $request->level_id
    ]);

    return redirect('/user');
    }

    public function ubah($id)
    {
        $users = UserModel::find($id);
        return view('user_ubah', ['data' => $users]);
    }

    public function ubah_simpan($id, Request $request)
    {
        $user = UserModel::find($id);

        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make($request->password);
        $user->level_id = $request->level_id;

        $user->save();

        return redirect('/user');
    }

    public function hapus($id)
    {
        $users = UserModel::find($id);
        $users->delete();

        return Redirect('/user');
    }
}
