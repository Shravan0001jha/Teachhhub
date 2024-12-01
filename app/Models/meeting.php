<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class meeting extends Model
{
    use HasFactory,SoftDeletes;

    public function batch(){
        return $this->belongsTo(Batch::class);
    }
}
