<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $table = 'appointments';
    protected $primaryKey = 'id';
    protected $timeStamps = true;
    protected $fillable = ['start','end','time_id','expert_id','consultation_id','appointmentable_type','appointmentable_id'];
   // protected $with = ['']
    public function time()
    {
        return $this->belongsTo(Time::class);
    }

    public function experts()
    {
        return $this->belongsTo(Expert::class);
    }
//
//    public function users()
//    {
//        return $this->belongsTo(User::class);
//    }

    public function appointmentable()
    {
        return $this->morphTo();
    }

    public function consultations()
    {
        return $this->belongsTo(Consultation::class);
    }
}
