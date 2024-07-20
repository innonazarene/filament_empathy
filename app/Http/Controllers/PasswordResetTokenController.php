<?php

namespace App\Http\Controllers;
use App\Models\PasswordResetToken;

use Illuminate\Http\Request;

class PasswordResetTokenController extends Controller
{
    //Index Example : http://127.0.0.1:8000/api/{{PREFIX}}/{{API-ROUTE}}/?with={{WITH-FUNCTION(found in models)}}&
    //orderBy={{FIELD-NAME}:{{ASC or DESC}}&limit={{LIMIT}}&fields={{FIELD}}&filter={{FILTER-FIELD}}:{{VALUE}}
    public function index(Request $request)
    {
        return response()->json($this->displayRequest(NULL,$request,new PasswordResetToken));
    }

    //create
    public function create()
    {}

    //store
    public function store(Request $request)
    {
        return PasswordResetToken::create($request->all());
    }

    //show Example : http://127.0.0.1:8000/api/{{PREFIX}}/{{API-ROUTE}}/{{ID}}?with={{WITH-FUNCTION(found in models)}}&
    //orderBy={{FIELD-NAME}:{{ASC or DESC}}&limit={{LIMIT}}&fields={{FIELD}}&filter={{FILTER-FIELD}}:{{VALUE}}
    public function show(Request $request, $id)
    {
        return response()->json($this->displayRequest($id,$request,new PasswordResetToken));
    }

    //edit
    public function edit($id)
    {}

    //update
    public function update(Request $request, $id)
    {
        
        $data = PasswordResetToken::find($id);
        $data->update($request->all());
        return response()->json(["message" => "", "data" => $data]);

    }

    //destroy
    public function destroy($id)
    {
        
        $data = PasswordResetToken::findOrFail($id);
        $data->delete();
        return response()->json(["message" => "", "data" => $data]);

    }

}
    //

