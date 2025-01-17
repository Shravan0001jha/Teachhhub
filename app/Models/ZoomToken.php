<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomToken extends Model
{
    use HasFactory;
    protected $guarded = [ "id" ];

    protected $dates = [
        'access_token_expires_at',
    ];
}
