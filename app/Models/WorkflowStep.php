<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    use HasFactory;
    protected $guarded=[],$hidden=['deleted_at'];


    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function workflowSelectableStatus()
    {
        return $this->hasMany(WorkflowStatus::class, 'selectable_in_workflow_step_id');
    }

}
