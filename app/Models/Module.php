<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $guarded=[],$hidden=['created_at','updated_at'];

    public function parent() {
        return $this->belongsTo(Module::class,'parent_id');
    }
}
