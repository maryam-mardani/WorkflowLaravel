<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    use HasFactory;
    protected $guarded=[],$hidden=['deleted_at'];


    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
