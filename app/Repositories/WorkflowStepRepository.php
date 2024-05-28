<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\WorkflowStep;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class WorkflowStepRepository implements RepositoryInterface
{
    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return  WorkflowStep::query();
    }

    public static function FindByField($field, $value)
    {
        return (new self)->query()->where($field, $value)->first();
    }

    public static function GetByField($field, $value)
    {
        return (new self)->query()->where($field, $value)->get();
    }

    public static function FindById($id)
    {
        return (new self)->query()->where('id', $id)->first();
    }

    static function NewItem($data): \Illuminate\Database\Eloquent\Model
    {
        return WorkflowStep::create($data);
    }
    static function UpdateItem($data, $id): int
    {
        $record = (new self )->FindByField("id",$id);
        foreach ($data as $key=>$value){
            $record->{$key}=$value;
        }
        return $record->save();
    }


    static function Builder(): Builder
    {
        return (new self)->query()->select('*');
    }


    static function GetActiveStarter()
    {
        return DB::table('workflow_steps')
                    ->join('workflows','workflow_steps.workflow_id', '=', 'workflows.id')
                    ->where('workflow_steps.is_starter','=',1)
                    ->whereIN('workflow_steps.role_id','=',auth()->user()->roles->pluck('id'))
                    ->select(
                        'workflow_steps.id',
                        'workflow_steps.workflow_id',
                        'workflows.title as workflow_title'
                    )
                    ->groupBy('workflows.id')
                    ->get();
    }


    static function Remove($id)
    {
        $record = (new self )->FindByField("id",$id);
        return $record ->delete();
    }
}
