<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Dependent as DependentResource;
use App\Http\Resources\Customer as CustomerResource;
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
        ]);

        // if get errors
        if ($validation->fails())
        {
            return response()->json($validation->errors());
        }

        $dependent = new Dependent();
        $dependent->fill($request->all());

        // get auth user id
        $dependent->user_id = $request->user()->id;

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
     * Display the specified customer of a dependent.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCustomer($id)
    {
        $dependent = Dependent::findOrFail($id);

        // get customer of the dependent
        $customer = $dependent->customer;

        // return as a resource
        return new CustomerResource($customer);
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
            // validate user id from request, if it's filled
            'user_id' => 'filled|integer|exists:users,id',
        ]);

        // if got errors
        if ($validation->fails())
        {
            return response()->json($validation->errors());
        }

        // get dependent from db
        $dependent = Dependent::findOrFail($id);

        // check if the customer belongs to the auth user
        if ($dependent->user_id != $request->user()->id)
        {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        // updated it
        $dependent->fill($request->all());
        $dependent->save();

        return new DependentResource($dependent);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // get dependente
        $dependent = Dependent::findOrFail($id);

        // check if the customer belongs to the auth user
        if ($dependent->user_id != $request->user()->id)
        {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        // deletes for real the dependent, this table do not use soft delete
        $dependent->delete();

        return response()->json([
            'message' => 'Dependent ID ' . $dependent->id . ' successfully deleted',
            'data' => $dependent
        ]);
    }
}
