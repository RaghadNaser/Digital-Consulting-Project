<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;
    protected $table = 'times';
    protected $primaryKey = 'id';
    protected $timeStamps = true;
    protected $fillable = ['start' , 'end','expert_id','day_id'];

    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function experts()
    {
        return $this->belongsTo(Expert::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
