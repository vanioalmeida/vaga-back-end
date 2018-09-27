<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Dependent as DependentResource;
use App\Rules\PhoneNumber;
use App\Requests;
use App\Dependent;
use Validator;

class DependentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all customers
        $dependents = Dependent::paginate(10);

        // return collection as a resource
        return DependentResource::collection($dependents);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate all fields
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'cellphone' => ['required',  new PhoneNumber],
            'customer_id' => 'required|integer|exists:customers,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // if get errors
        if ($validation->fails())
        {
            return response()->json($validation->errors());
        }

        $dependent = new Dependent();
        $dependent->fill($request->all());
        $dependent->save();

        return new DependentResource($dependent);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get dependent
        $dependent = Dependent::findOrFail($id);

        return new DependentResource($dependent);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validate dependent
        $validation = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'email',
            'cellphone' => ['filled',  new PhoneNumber],
            'customer_id' => 'integer|exists:customers,id',
            'user_id' => 'integer|exists:users,id',
        ]);

        // if got errors
        if ($validation->fails())
        {
            return response()->json($validation->errors());
        }

        // get dependent from db
        $dependent = Dependent::findOrFail($id);
        // updated it
        $dependent->fill($request->all());
        $dependent->save();

        return new DependentResource($dependent);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // get dependente
        $dependent = Dependent::findOrFail($id);

        // it does not delete his/her dependents
        $dependent->delete();

        return response()->json([
            'message' => 'Dependent ID ' . $dependent->id . ' successfully deleted',
            'data' => $dependent
        ]);
    }
}
