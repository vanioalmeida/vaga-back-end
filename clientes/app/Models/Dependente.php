<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class dependente extends Model
{
    protected $fillable = [
        'user_id', 'cliente_id', 'nome', 'email', 'celular',
    ];

    public function cliente() {
        return $this->belongsTo('App\Model\Cliente', 'cliente_id', 'id');
    }
    
    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }    
}
