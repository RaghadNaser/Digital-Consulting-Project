<?php

namespace App\Models;


use App\Models\Time;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Expert extends  Authenticatable
{
    use HasFactory,HasApiTokens;
    protected $guard = 'expert';
    protected $table = 'experts';
    protected $primaryKey = 'id';
    protected $timeStamps = true;
    public $with = ['consultations'];
    protected $fillable = ['name','img','phone','address','balance','experiences','email','password'];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function appointmentMorph()
    {
        return $this->morphMany(Appointment::class, 'appointmentable');
    }
    public function times()
    {
        return $this->hasMany(Time::class);
    }


    public function consultations()
    {
        return $this->belongsToMany(Consultation::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }
    public function records()
    {
        return $this->hasMany(Record::class);
    }
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
