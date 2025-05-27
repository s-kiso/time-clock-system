<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'year',
        'month',
        'day',
        'clock_in',
        'clock_out',
        'notes'
    ];

    public function rest()
    {
        return $this->hasMany(Rest::class);
    }

    public function modify_request()
    {
        return $this->hasOne(Modify_request::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
