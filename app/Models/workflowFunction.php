<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workflowFunction extends Model
{
    use HasFactory;
    protected $guarded=[],$hidden=['deleted_at'];

}
