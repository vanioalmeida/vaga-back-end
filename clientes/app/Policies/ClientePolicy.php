<?php

namespace App\Policies;

use App\User;
use App\Models\Cliente;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Cliente $cliente) {
        return TRUE;
    }

    public function create(User $user) {
        return $user->id > 0;
    }

    public function update(User $user, Cliente $cliente) {
        return $user->id === $cliente->user_id;
    }

    public function delete(User $user, Cliente $cliente) {
        return $user->id === $cliente->user_id;
    }
}
