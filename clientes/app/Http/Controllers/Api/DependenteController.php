<?php

namespace App\Http\Controllers\API;

use Hash;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Dependente;
use App\User;
use Illuminate\Support\Facades\Auth; 

class DependenteController extends Controller
{
    public function index() {
        $dependentes = Dependente::all();
        return response()->json($dependentes);
    }

    public function create() {
        //AGUARDANDO VIEW
    }

    public function store(Request $request) {
        $dados = $request->all();

        $validator = Validator::make($dados, [
            'nome' => 'required|max:100',
            'email' => 'required|email|unique:dependentes',
            'celular' => 'required|numeric',
            'cliente_id' => 'required|numeric',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message'   => 'Validation Failed',
                'errors'    => $validator->errors()->all()
            ], 422);
        }

        $cliente = Cliente::find($request->cliente_id);

        if(!$cliente) {
            return response()->json([
                'message'   => 'Cliente not found',
            ], 404);
        }          

        $dependente = new Dependente();
        $dependente->fill($dados);
        $dependente->user_id = Auth::user()->id;
        $dependente->save();

        return response()->json($dependente, 201);
    }

    public function show($id) {
        $dependente = Dependente::find($id);

        if(!$dependente) {
            return response()->json([
                'message'   => 'Dependente not found',
            ], 404);
        }

        return response()->json($dependente,201);
    }

    public function edit($id) {
        //AGUARDANDO VIEW
    }

    public function update(Request $request, $id) {
        $dependente = Dependente::find($id);
        $dados = $request->all();

        if(!$dependente) {
            return response()->json([
                'message'   => 'Dependente not found',
            ], 404);
        }        

        if(array_key_exists('email',$dados) && $dependente->email == $dados['email']) {
            unset($dados['email']);
        }


        $validator = Validator::make($dados, [
            'nome' => 'required|max:100',
            'email' => 'required|email|unique:dependentes',
            'celular' => 'required|numeric',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message'   => 'Validation Failed',
                'errors'    => $validator->errors()->all()
            ], 422);
        }
        
        $dependente->fill($dados);
        $dependente->save();

        return response()->json($dependente,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dependente = Dependente::find($id);
        if(!$dependente) {
            return response()->json([
                'message'   => 'Dependente not found',
            ], 404);
        }

        $user = Auth::user();
        return response()->json($dependente->delete(), 204);
    }


}
