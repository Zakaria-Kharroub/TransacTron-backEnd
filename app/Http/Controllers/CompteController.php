<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compte;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Str;





class CompteController extends Controller
{
    //
    public function createCompte(Request $request){
        try {
            
            $existingCompte = Compte::where('user_id', $request->user_id)
                                    ->where('type', $request->type)
                                    ->first();
            if ($existingCompte) {
                return response()->json([
                    'message' => 'utilisateur possed deja compte de ce type',
                ], Response::HTTP_CONFLICT);
            }
    
            $compte = new Compte();
            $compte->numero_compte = rand(1000000000, 9999999999); 
            $compte->solde = $request->solde;
            $compte->user_id = $request->user_id;
            $compte->type = $request->type;
            $compte->save();
    
            return response()->json([
                'message' => 'compte created successfully',
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error de creation de compte: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
