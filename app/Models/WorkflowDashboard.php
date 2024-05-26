<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowDashboard extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function workflowInstance()
    {
        return $this->belongsTo(WorkflowInstance::class, 'workflow_instance_id');
    }

    public function role()
    {
        return $this->belongsTo(Role ::class, 'role_id');
    }

    public function workflowStep()
    {
        return $this->belongsTo(WorkflowStep ::class, 'workflow_step_id');
    }
}
