<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyMaterialBatch extends Model
{
    use HasFactory;

    //fillable
    protected $fillable = [
        'study_material_id',
        'batch_id',
    ];
    public function batch(){
        return $this->belongsTo(Batch::class);
    }
}
