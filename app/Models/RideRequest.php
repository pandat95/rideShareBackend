<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RideRequest extends Model
{
    use HasFactory;
    protected $table='ride_request';
    protected $fillable=
    [

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
    
    

    public function student()
    {
        return $this->belongsTo(Student::class,'studentID','stu_id');
    }

    public function driver()
    {
        return $this->belongsTo(Student::class,'driverID','stu_id');
    }
}
