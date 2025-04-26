<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Casts\Attribute;


class UserModel extends Authenticatable implements JWTSubject
{
    use HasFactory;

    public function getJWTIdentifier(){
      return $this->getKey();
    }

    public function getJWTCustomClaims(){
      return [];
    }

    protected $table = 'm_user';    
    protected $primaryKey ='user_id';//mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = ['level_id', 'username', 'nama','password', 'foto', 'image'];

    protected $hidden = ['password']; //jangan ditampilkan saat select

    protected $casts = ['password' => 'hashed']; //casting password agar otomatis di hash


    public function level(){
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }
    protected function image(): Attribute{
      return Attribute::make(
        get: fn ($image) => url('/storage/posts/' . $image),
      );
    }


    /**
     * Mendapatkan nama role
     */

     public function getRoleName(): string {
        return $this->level->level_nama;
     }

     /**
      * cek apakah user memiliki role tertentu
      */

      public function hasRole($role): bool {
        return $this->level->level_kode == $role;
      }

      /**
       * mendapatkan kode role
       */
      
      public function getRole(){
        return $this->level->level_kode;
      }
}
