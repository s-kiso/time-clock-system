<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modify_request extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'status',
        'user_id',
        'year',
        'month',
        'day',
        'clock_in',
        'clock_out',
        'notes'
    ];

    public function Record()
    {
        return $this->belongsTo(Record::class);
    }

    public function Modify_request_rest()
    {
        return $this->hasMany(Modify_request_rest::class);
    }
}
