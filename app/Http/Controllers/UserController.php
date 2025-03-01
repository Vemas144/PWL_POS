<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
    //     //tambah data user dengan Elequent Model
    //      $data = [
    //        'username' => 'customer-1',
    //        'nama' => 'Pelanggan',
    //        'password' => Hash::make('12345'),
    //        'level_id' => 4
    //    ];
    //     UserModel::insert($data); //tambahakan dat ke tabel m_user

        $data = [
            'nama' => 'Pelanggan Pertama'
        ];
        UserModel::where('username', 'customer-1')->update($data);

        //coba akses model UserModel
        $users = UserModel::all(); //ambil semua data dari tabel m_user
        return view('user', ['data' => $users]);
    }
}
