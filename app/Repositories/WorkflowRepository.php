<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\Workflow;
use Illuminate\Database\Eloquent\Builder;

class WorkflowRepository implements RepositoryInterface
{
    private function query(): \Illuminate\Database\Eloquent\Builder
    {
        return  Workflow::query();
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
        return Workflow::create($data);
    }

    static function UpdateItem($data, $id): int
    {
        $record = (new self)->FindByField("id", $id);
        foreach ($data as $key => $value) {
            $record->{$key} = $value;
        }
        return $record->save();
    }

    static function Builder(): Builder
    {
        return (new self)->query()->select('*')->orderBy('id','desc');
    }

    static function Remove($id)
    {
        $record = (new self)->FindByField("id", $id);
        return $record->delete();
    }

}
