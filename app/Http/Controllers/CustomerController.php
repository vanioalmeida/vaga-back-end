<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Customer as CustomerResource;
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
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // if got errors
        if ($validation->fails())
        {
            return response()->json($validation->errors());
        }

        $customer = new Customer();
        $customer->fill($request->all());
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
            'user_id' => 'integer|exists:users,id',
        ]);

        // if got errors
        if ($validation->fails())
        {
            return response()->json($validation->errors());
        }

        // get customer from db
        $customer = Customer::findOrFail($id);
        // updated it
        $customer->fill($request->all());
        $customer->save();

        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // get costumer
        $customer = Customer::findOrFail($id);

        // it does not delete his/her dependents
        $customer->delete();

        return response()->json([
            'message' => 'Customer ID ' . $customer->id . ' successfully deleted',
            'data' => $customer
        ]);
    }
}
