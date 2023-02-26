<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;
    protected $table = 'evaluations';
    protected $primaryKey = 'id';
    protected $timeStamps = true;
    protected $fillable = ['rating','expert_id','evaluationable_id','evaluationable_type'];
    protected $with = ['experts'];
    public function experts()
    {
        return $this->belongsTo(Expert::class);
    }
//    public function users()
//    {
//        return $this->belongsTo(User::class);
//    }
    public function evaluationable()
    {
        return $this->morphTo();
    }
}
