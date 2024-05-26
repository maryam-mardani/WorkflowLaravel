<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowStatus extends Model
{
    use HasFactory;
    protected $guarded=[],$hidden=['deleted_at'];


    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function workflowFunction()
    {
        return $this->belongsTo(workflowFunction::class, 'workflow_function_id');
    }

    public function selectableWorkflowStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'selectable_in_workflow_step_id');
    }

    public function nextWorkflowStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'next_workflow_step_id');
    }
}
