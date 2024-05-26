<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowHistory extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function workflowInstance()
    {
        return $this->belongsTo(WorkflowInstance::class, 'workflow_instance_id');
    }

    public function user()
    {
        return $this->belongsTo(User ::class, 'user_id');
    }

    public function workflowStep()
    {
        return $this->belongsTo(WorkflowStep ::class, 'workflow_step_id');
    }
    public function workflowStatus()
    {
        return $this->belongsTo(WorkflowStatus ::class, 'workflow_status_id');
    }


}
