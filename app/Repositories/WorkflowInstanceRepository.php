<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\WorkflowInstance;
use Illuminate\Database\Eloquent\Builder;

class WorkflowInstanceRepository implements RepositoryInterface
{
    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return  WorkflowInstance::query();
    }

    public static function FindByField($field, $value)
    {
        return (new self)->query()->where($field, $value)->first();
    }

    public static function FindById($id)
    {
        return (new self)->query()->where('id', $id)->first();
    }

    static function NewItem($data): \Illuminate\Database\Eloquent\Model
    {
        return WorkflowInstance::create($data);
    }

    static function AddInstance($module,$data): \Illuminate\Database\Eloquent\Model
    {
        return $module->workflowInstance()->create($data);
    }


    //Instance have not be updated
    static function UpdateItem($data, $id): int
    {
        return 0;
    }
    
    static function Builder(): Builder
    {
        return (new self)->query()->select('*');
    }


    static function Remove($id)
    {
        $record = (new self )->FindByField("id",$id);
        return $record ->delete();
    }

}
