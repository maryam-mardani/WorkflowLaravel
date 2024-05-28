<?php
namespace App\Traits;

use App\Repositories\WorkflowDashboardRepository;
use App\Repositories\WorkflowHistoryRepository;
use App\Repositories\WorkflowInstanceRepository;
use App\Repositories\WorkflowRepository;
use App\Repositories\WorkflowStatusRepository;
use App\Repositories\WorkflowStepRepository;
use Carbon\Carbon;

trait TraiteWorkflow
{
    public $workflow_step;
    public $next_workflow_step;
    public $next_workflow_step_is_public;
    public $next_workflow_step_role_id;
    public $next_workflow_step_user_id;
    public $error_message;

    public $workflow;
    public $workflow_id;
    public $workflow_description;
    public $workflow_status_id;
    public $workflow_dashboard;
    public $workflow_dashboard_id;
    public $main_module_id;
    public $main_module_type;
    public $workflow_instance_id;

    public $workflowStatuses = [];

    public function workflowProcess($item,$workflow_id,$dashboard_id = 0)
    {
        $this->generateWorkflow($workflow_id,$dashboard_id);

        if(empty($this->error_message))
        {
            $this->getWorkflowInstance($item,$workflow_id,$dashboard_id);
            $this->workflowFunction();
            $this->insertToHistory();
        }
    }


    public function getWorkflowInstance($item,$workflow_id,$dashboard_id)
    {
        if($dashboard_id){
            $this->workflow_instance_id = $this->workflow_dashboard->workflowInstance->id;
        }
        else{
            $this->workflow_instance_id = WorkflowInstanceRepository::AddInstance($item,['workflow_id' => $workflow_id])->id;
        }
    }

    public function workflowFunction()
    {
        $function = WorkflowStatusRepository::FindById($this->workflow_status_id)->workflowFunction->function;
        switch($function)
        {
            case 'NoAction':
                $this->updateDashboardDescription();
                break;
            case 'NextStep':
                $this->getNextStep('sync');
                $this->removeAllDashboard();
                $this->insertToDashboard();
                break;
            case 'PrevStep':
                $this->getPrevStep();
                $this->removeCurrentDashboard();
                $this->insertToDashboard();
                break;
            case 'Archive':
                $this->removeAllDashboard();
                break;
        }
    }

      public function insertToHistory()
    {
        $data = [];
        $data['workflow_instance_id'] = $this->workflow_instance_id;
        $data['workflow_step_id'] = $this->workflow_step->id;
        $data['workflow_status_id'] = $this->workflow_status_id;
        $data['role_id'] =  $this->workflow_dashboard?->role_id;
        $data['description'] = $this->workflow_description;
        $item = WorkflowHistoryRepository::NewItem($data);
    }


    public function removeCurrentDashboard()
    {
        if(!empty($this->workflow_dashboard_id))
        {
            WorkflowDashboardRepository::Remove($this->workflow_dashboard_id);
        }
    }

    public function removeAllDashboard()
    {
        $rows = WorkflowDashboardRepository::Builder()->where('workflow_instance_id','=',$this->workflow_instance_id)->get();
        if(!empty($rows))
        {
            foreach($rows as $item)
            {
                WorkflowDashboardRepository::Remove($item->id);
            }
        }
    }


    public function insertToDashboard()
    {
        $data = [];
        $data['workflow_instance_id'] = $this->workflow_instance_id;
        $data['workflow_step_id'] = $this->next_workflow_step->id;
        $data['description'] = $this->workflow_description;

        $data['role_id'] = (!empty($this->next_workflow_step_role_id)) ? $this->next_workflow_step_role_id : NULL;
        $data['user_id'] = (!empty($this->next_workflow_step_user_id)) ? $this->next_workflow_step_user_id : NULL;

        $item = WorkflowDashboardRepository::NewItem($data);
        $this->uploadForDashboard($item);

    }

    public function updateDashboardDescription()
    {
        WorkflowDashboardRepository::UpdateItem(
           [
            'description' => $this->workflow_description,
            'status' => 0
           ],
           $this->workflow_dashboard_id
        );
    }

    public function getPrevStep()
    {
        $result = WorkflowHistoryRepository::Builder()
                                ->whereHas('workflowStatus', function($q){
                                    $q->where('next_workflow_step_id','=',$this->workflow_step->id);
                                })
                                ->where('workflow_instance_id','=',$this->workflow_instance_id)
                                ->where('workflow_step_id','!=',$this->workflow_step->id)
                                ->orderBy('id','DESC')
                                ->first();

                                if(empty($result))
        {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => __('The previous step not found')
            ]);

        }
        else
        {
            $this->next_workflow_step = $result->workflowStep;
            $this->next_workflow_step_role_id = $result->role_id;
            $this->next_workflow_step_user_id = $result->user_id;
        }
    }

    public function getNextStep()
    {
        $this->next_workflow_step = WorkflowStatusRepository::FindById($this->workflow_status_id)?->nextWorkflowStep;

        //If role not selected in workflow, need to fetch from step definition
        if(empty($this->next_workflow_step_role_id)) $this->next_workflow_step_role_id = $this->next_workflow_step->role_id;

        if(empty($this->next_workflow_step)){
            $this->error_message =  __('The next step not defined');
        }
        elseif(!$this->next_workflow_step_is_public && empty($this->next_workflow_step_role_id)){
            $this->error_message =  __('The next step permission not defined');
        }
    }



    public function generateWorkflow($workflow_id,$dashboard_id)
    {
        $this->workflow_dashboard_id = $dashboard_id;
        $this->workflow_id = $workflow_id;
        $is_starter = ($this->workflow_dashboard_id) ? 0 : 1;

        //Get Workflow
        $this->workflow = WorkflowRepository::FindById($this->workflow_id);
        if(empty($this->workflow) || empty($this->workflow->module))
        {
            $this->error_message = __('The workflow not defined correctly');
        }
        else
        {
            //Get Workflow Information
            if($is_starter)
            {
                $this->workflow_step = WorkflowStepRepository::Builder()->where('workflow_id','=', $this->workflow_id)->where('is_starter','=', 1)->first();
                $this->workflow_status_id = $this->workflow_step->workflowSelectableStatus->first()->id;
            }
            else
            {
                $this->workflow_dashboard = WorkflowDashboardRepository::FindById($this->workflow_dashboard_id);
                $this->main_module_id = $this->workflow_dashboard->workflowInstance->main_module_id;
                $this->main_module_type = $this->workflow_dashboard->workflowInstance->main_module_type;
                $this->workflow_step = $this->workflow_dashboard?->workflowStep;
                if(empty($this->next_workflow_step_group_id)) $this->next_workflow_step_group_id = $this->workflow_dashboard?->group_id;
            }

            if(!$this->havePermission($is_starter))
            {
                $this->error_message = __("You don't have permission");
            }
            if(empty($this->workflow_step) || empty($this->main_module_action) || empty($this->main_module_action->component_path))
            {
                $this->error_message =  __("The step not defined");
            }
            elseif(empty($this->workflow_step->workflowSelectableStatus))
            {
                $this->error_message = __('The statuses not defined');
            }

            if(!$is_starter)
            {
                if(empty($this->workflow_dashboard))
                {
                    $this->error_message = __("You don't have permission");
                }
                else
                {
                    $this->setReadStatus();
                    $this->setAvailableStatuses();
                }
            }
        }
    }

    public function havePermission($is_starter)
    {
        if(!$is_starter)
        {
            return (!$this->workflow_dashboard ||
                    !($this->workflow_dashboard->role == null
                    || in_array($this->workflow_dashboard->role->id,auth()->user()->roles->pluck('id'))))
                    ? false : true;
        }
        else
        {
            return (!WorkflowStepRepository::GetActiveStarter()->count()) ? false : true;
        }
    }

    public function setReadStatus()
    {
        if($this->workflow_dashboard->status == 0)
        {
            $this->workflow_dashboard->status = 1;
            $this->workflow_dashboard->view_date = Carbon::now();
            $this->workflow_dashboard->save();
        }
    }

    public function setAvailableStatuses()
    {
        //This method is called in another render, so we neet to get dashboard id from input
        $this->workflowStatuses = WorkflowDashboardRepository::FindById($this->workflow_dashboard_id)->workflowStep->workflowSelectableStatus;
    }

}
