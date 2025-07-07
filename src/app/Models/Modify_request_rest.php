<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modify_request_rest extends Model
{
    use HasFactory;

    protected $fillable = [
        'modify_request_id',
        'record_id',
        'start',
        'end',
    ];

    public function Modify_request()
    {
        return $this->belongsTo(Modify_request::class);
    }
}
