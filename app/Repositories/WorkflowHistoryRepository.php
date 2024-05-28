<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\WorkflowHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class WorkflowHistoryRepository implements RepositoryInterface
{
    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return  WorkflowHistory::query();
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
        $data["user_id"] = Auth::user()->id;
        return WorkflowHistory::create($data);
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

    static function Remove($id)
    {
        $record = (new self )->FindByField("id",$id);
        return $record ->delete();
    }

}
