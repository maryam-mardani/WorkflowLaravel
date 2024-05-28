<?php

namespace App\Repositories;

use App\Filter\Title;
use App\Interfaces\RepositoryInterface;
use App\Models\Module;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;


class ModuleRepository implements RepositoryInterface
{
    private function query(): \Illuminate\Database\Eloquent\Builder
    {
        return  Module::query();
    }

    public static function FindByField($field, $value)
    {
        return (new self)->query()->where($field, $value)->first();
    }

    public static function FindById($id)
    {
        return (new self)->query()->where('id', $id)->first();
    }

    public static function FindByFields($params)
    {
        $query = (new self)->query();
        foreach($params as $key=>$value){
            $query=$query->where($key, $value);
        }
        return $query->first();
    }
    static function Builder() : Builder {
        return (new self)->query()->select('*');
    }

    public static function GetByFields($params)
    {
        $query = (new self)->query();
        foreach($params as $key=>$value){
            $query=$query->where($key, $value);
        }
        return $query->get();
    }

    static function NewItem($data): \Illuminate\Database\Eloquent\Model
    {

        return  Module::create($data);
    }
    static function UpdateItem($data, $id): int
    {
        return  Module::where('id', $id)->update([
                "title" => $data['title'],

            ]);
    }
    static function Remove($id)
    {
        $record = (new self )->FindByField("id",$id);
        return $record ->delete();
    }
}
