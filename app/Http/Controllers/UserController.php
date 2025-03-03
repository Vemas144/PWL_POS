<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
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
        $users = UserModel::where('username', 'manager9')->findOrFail(1);
        return view('user', ['data' => $users]);
    }
}
