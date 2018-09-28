<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model {
    protected $fillable = [
        'user_id', 'nome', 'email', 'telefone',
    ];

    public function dependentes() {
        return $this->hasMany('App\Models\Cliente');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
