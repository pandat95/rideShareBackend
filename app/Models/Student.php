<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\PostRideOffer;

class Student extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'student';
    protected $primaryKey = 'stu_id';

    protected $fillable = [
        'stu_id', 'first_name', 'last_name', 'email', 'gender', 'phone', 'address', 'password', 'api_token',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function PostRideOffer()
    {
        return $this->belongsToMany(PostRideOffer::class);
    }

    public function PostRideRequest()
    {
        return $this->belongsToMany(PostRideRequest::class);
    }

    public function RideOffer()
    {
        return $this->belongsToMany(RideOffer::class);
    }

    public function RideRequest()
    {
        return $this->belongsToMany(RideRequest::class);
    }
}
