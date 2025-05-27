<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modify_request extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'status'
    ];

    public function Record()
    {
        return $this->belongsTo(Record::class);
    }
}
