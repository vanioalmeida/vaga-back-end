<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Customer as CustomerResource;
use App\Http\Resources\Dependent as DependentResource;
use App\User;

class UserController extends Controller
{
    /**
     * Display all customers user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function customers($id)
    {
        $user = User::findOrFail($id);

        // get all customers user
        $customers = $user->customers;

        // return as a resource
        return CustomerResource::collection($customers);
    }

    /**
     * Display all dependents user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dependents($id)
    {
        $user = User::findOrFail($id);

        // get all customers user
        $customers = $user->dependents;

        // return as a resource
        return DependentResource::collection($customers);
    }
}
