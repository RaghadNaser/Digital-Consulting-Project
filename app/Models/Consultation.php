<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;
    protected $table = 'consultations';
    protected $primaryKey = 'id';
    protected $timeStamps = true;
    protected $fillable = ['name','cost'];
    public function experts()
    {
        return $this->belongsToMany(Expert::class);
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
