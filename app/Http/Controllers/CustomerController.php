<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Customer as CustomerResource;
use App\Http\Resources\Dependent as DependentResource;
use App\Rules\PhoneNumber;
use App\Requests;
use App\Customer;
use Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all customers
        $customers = Customer::paginate(10);

        // return collection as a resource
        return CustomerResource::collection($customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate customer
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'telephone' => ['required',  new PhoneNumber],
        ]);

        // if got errors
        if ($validation->fails())
        {
            return response()->json($validation->errors());
        }

        $customer = new Customer();
        $customer->fill($request->all());

        // get auth user id
        $customer->user_id = $request->user()->id;

        $customer->save();

        return new CustomerResource($customer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get customer
        $customer = Customer::findOrFail($id);

        return new CustomerResource($customer);
    }

    /**
     * Display all dependents that belongs to a customer id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showDependents($id)
    {
        $customer = Customer::findOrFail($id);

        // get all dependents of that customer
        $dependents = $customer->dependents;

        // return collection as a resource
        return DependentResource::collection($dependents);
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
        // validate customer
        $validation = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'email',
            'telephone' => ['filled', new PhoneNumber],
            // validate user id from request, if it's filled
            'user_id' => 'filled|integer|exists:users,id',
        ]);

        // if got errors
        if ($validation->fails())
        {
            return response()->json($validation->errors());
        }

        // get customer from db
        $customer = Customer::findOrFail($id);

        // check if the customer belongs to the auth user
        if ($customer->user_id != $request->user()->id)
        {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        // updated it
        $customer->fill($request->all());
        $customer->save();

        return new CustomerResource($customer);
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
        // get costumer
        $customer = Customer::findOrFail($id);

        // check if the customer belongs to the auth user
        if ($customer->user_id != $request->user()->id)
        {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        // it does not delete his/her dependents
        // and this table has soft delete
        $customer->delete();

        return response()->json([
            'message' => 'Customer ID ' . $customer->id . ' successfully deleted',
            'data' => $customer
        ]);
    }
}
