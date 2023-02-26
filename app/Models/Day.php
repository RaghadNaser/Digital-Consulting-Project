<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;
    protected $table = 'days';
    protected $primaryKey = 'id';
    protected $timeStamps = true;
    protected $fillable = ['name'];

    public function times()
    {
        return $this->hasMany(Time::class);
    }
}
