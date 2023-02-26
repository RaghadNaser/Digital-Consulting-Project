<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;
    protected $table = 'favourites';
    protected $primaryKey = 'id';
    protected $timeStamps = true;
    protected $fillable = ['expert_id','user_id'];
    protected $with = ['experts'];
    public function experts()
    {
        return $this->belongsTo(Expert::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
