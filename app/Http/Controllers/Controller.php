<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public $requestRules = [
        'with' => 'string',
        'orderBy' =>'string',
        'limit' => 'integer',
        'fields' => 'string',
        'filter' => 'string'
    ];
    public function displayRequest($id='',Request $request, $model)
    {
        $validatedData = $request->validate($this->requestRules);
        $query = $model->query();
        if($id != ''){
            return  $query->where('id',$id)->get();
        }
        if (isset($validatedData['with'])) {
            $query->with(explode(',', $validatedData['with']));
        }
        if (isset($validatedData['orderBy'])) {
            $orderByParams = explode(',', $validatedData['orderBy']);
            foreach ($orderByParams as $orderByParam) {
                $orderByArray = explode(':', $orderByParam);
                $query->orderBy($orderByArray[0], $orderByArray[1] ?? 'asc');
            }
        }
        if (isset($validatedData['filter'])) {
            $filterParams = explode(',', $validatedData['filter']);
            foreach ($filterParams as $filterParam) {
                $filterArray = explode(':', $filterParam);
                $column = $filterArray[0];
                $value = $filterArray[1] ?? null;
                if ($value !== null) {
                    $query->where($column, $value);
                }
            }
        }
        if (isset($validatedData['limit'])) {
            $query->limit($validatedData['limit']);
        }
        if (isset($validatedData['fields'])) {
            $query->select(explode(',', $validatedData['fields']));
        }
        $results = $query->get();
        return $results;
    }
}
