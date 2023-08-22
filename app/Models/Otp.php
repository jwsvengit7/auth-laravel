<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;  
use App\Models\User;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Otp extends Model  
{
    use HasFactory, Notifiable;  

    protected $fillable = [
        'user_id',
        'otp',
    ];

  
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
