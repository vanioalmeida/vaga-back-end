<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Set relationship between User and Customers
     */
    public function customers()
    {
        return $this->hasMany('App\Customer');
    }

    /**
     * Set relationship between User and Dependents
     */
    public function dependents()
    {
        return $this->hasMany('App\Dependent');
    }
}
