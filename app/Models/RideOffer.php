<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RideOffer extends Model
{
    use HasFactory;
    protected $table='ride_offer';

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
        'passengerID',
        'manufacturer',
        'model',
        'color',
        'plates_number',
    ];

    public $incrementing=false;
    
    

    public function student()
    {
        return $this->belongsTo(Student::class,'studentID','stu_id');
    }

    public function passenger()
    {
        return $this->belongsTo(Student::class,'passengerID','stu_id');
    }
}
