<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rest extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'start',
        'end'
    ];

    public function Record()
    {
        return $this->belongsTo(Record::class);
    }
}
