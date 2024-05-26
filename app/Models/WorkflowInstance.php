<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WorkflowInstance extends Model
{
    use HasUuids;
    use HasFactory;
    protected $hidden=[],$guarded=[];


    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function related_module(): MorphTo
    {
        return $this->morphTo();
    }

    public function histories()
    {
        return $this->hasMany(WorkflowHistory::class, 'workflow_instance_id');
    }

    public function last_history()
    {
        return $this->hasOne(WorkflowHistory::class, 'workflow_instance_id')->latestOfMany();
    }
}
