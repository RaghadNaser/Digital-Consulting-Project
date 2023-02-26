<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulting extends Model
{
    use HasFactory;
    protected $table = 'consultings';
    protected $primaryKey = 'id';
    protected $timeStamps = true;
    protected $fillable = ['name'];
    public $with = ['experts'];

    public function experts()
    {
        return $this->belongsToMany(Expert::class);
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }
}
