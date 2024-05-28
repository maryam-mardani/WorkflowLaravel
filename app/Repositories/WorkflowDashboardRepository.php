<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\WorkflowDashboard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class WorkflowDashboardRepository implements RepositoryInterface
{
    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return  WorkflowDashboard::query();
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
        return WorkflowDashboard::create($data);
    }
    static function UpdateItem($data, $id): int
    {
        $record = (new self )->FindByField("id",$id);
        foreach ($data as $key=>$value){
            $record->{$key}=$value;
        }
        return $record->save();
    }

    static function Builder($type = ''): Builder
    {
        switch($type)
        {
            default:
                return (new self)->query()->select('*');
                break;
            case 'receivedWorkflowDashboard':
                return (new self)->query()
                    // ->whereIn('group_id',UserRepository::GetActiveUserGroups())
                    ->where(function ($query){
                        $query->where('user_id','=',Auth::user()->id);
                        $query->orWhereNUll('user_id');
                    })
                    ->select('*')->orderBy('id','desc');
                break;
        }
    }

    static function Remove($id)
    {
        $record = (new self )->FindByField("id",$id);
        return $record ->delete();
    }

 
}
