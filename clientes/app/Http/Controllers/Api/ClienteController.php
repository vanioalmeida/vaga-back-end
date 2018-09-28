<?php

namespace App\Http\Controllers\API;

use Hash;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\User; 
use Illuminate\Support\Facades\Auth; 

class ClienteController extends Controller
{
    public function index() {
        $clientes = Cliente::all();
        return response()->json($clientes);
    }

    public function create() {
        //AGUARDANDO VIEW
    }

    public function store(Request $request) {
        $dados = $request->all();

        $validator = Validator::make($dados, [
            'nome' => 'required|max:100',
            'email' => 'required|email|unique:clientes',
            'telefone' => 'required|numeric'
        ]);

        if($validator->fails()) {
            return response()->json([
                'message'   => 'Validation Failed',
                'errors'    => $validator->errors()->all()
            ], 422);
        }

        $cliente = new Cliente();
        $cliente->fill($dados);
        $cliente->user_id = Auth::user()->id;
        $cliente->save();

        return response()->json($cliente, 201);
    }

    public function show($id) {
        $cliente = Cliente::find($id);

        if(!$cliente) {
            return response()->json([
                'message'   => 'Cliente not found',
            ], 404);
        }

        return response()->json($cliente,201);
    }

    public function edit($id) {
        //AGUARDANDO VIEW
    }

    public function update(Request $request, $id) {
        $cliente = Cliente::find($id);
        $dados = $request->all();

        if(!$cliente) {
            return response()->json([
                'message'   => 'Cliente not found',
            ], 404);
        }        

        if(array_key_exists('email',$dados) && $cliente->email == $dados['email']) {
            unset($dados['email']);
        }


        $validator = Validator::make($dados, [
            'nome' => 'required|max:100',
            'email' => 'required|email|unique:clientes',
            'telefone' => 'required|numeric'            
        ]);

        if($validator->fails()) {
            return response()->json([
                'message'   => 'Validation Failed',
                'errors'    => $validator->errors()->all()
            ], 422);
        }
        
        $cliente->fill($dados);
        $cliente->save();

        return response()->json($cliente,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        if(!$cliente) {
            return response()->json([
                'message'   => 'Cliente not found',
            ], 404);
        }

        $user = Auth::user();
        if ($user->can('delete', $cliente)) {
            return response()->json($cliente->delete(), 204);
        } else {
            response()->json([
                'message'   => 'Authorization error',
                'errors'    => 'Not authorized'
            ], 422);
        }        
    }
}
