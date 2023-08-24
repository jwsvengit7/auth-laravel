<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class  User extends Authenticatable implements JWTSubject

{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'interest',
        'status',
        'password_reset_token',
        'password_reset_token_created_at',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

///     jwt
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($password)
    {
        if ( $password !== null & $password !== "" ) {
            $this->attributes['password'] = bcrypt($password);
        }
    }
    public function createPasswordResetToken()
    {
        $this->password_reset_token = Str::random(60);
        $this->password_reset_token_created_at = now();
        $this->save();
    }
}
