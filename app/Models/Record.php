<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;
    protected $table = 'records';
    protected $primaryKey = 'id';
    protected $timeStamps = true;
    protected $fillable = [];
    public function experts()
    {
        return $this->belongsTo(Expert::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
