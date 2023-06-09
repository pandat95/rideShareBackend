<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostRideRequest extends Model
{
    use HasFactory;
    protected $table='post_ride_request';

    protected $fillable=
    [
        'title',
        'date',
        'time',
        'smoking',
        'eating',
        'pickup_loc_latitude',
        'pickup_loc_longitude',
        'destination_latitude',
        'destination_longitude',
        'gender',
        'studentID',
        'driverID',
        
    ];

    public $incrementing=false;
    
    protected $dates=['date','time'];

    public function student()
    {
        return $this->belongsTo(Student::class,'studentID','stu_id');
    }

    public function driver()
    {
        return $this->belongsTo(Student::class,'driverID','stu_id');
    }


}
