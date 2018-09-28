<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dependent extends Model
{
    // This table is not "SoftDeleted"
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'cellphone',
        'customer_id',
        'user_id'
    ];

    /**
     * Set relationship between Dependent and Customer
     */
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }
}
