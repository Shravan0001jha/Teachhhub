<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyMaterial extends Model
{
    use HasFactory;
    
    public function batches()
    {
        return $this->hasMany(StudyMaterialBatch::class);
    }
}
