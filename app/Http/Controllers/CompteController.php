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


    public function searchCompte($name){
        try {
           $user = User::where('name', $name)->first();
    
            if (!$user) {
                return response()->json([
                    'message' => 'user not found',
               ], Response::HTTP_NOT_FOUND);
            }
    
             $compte = Compte::where('user_id', $user->id)->first();
    
            if (!$compte) {
                return response()->json([
                  'message' => 'de user avez pas compte',
                ], Response::HTTP_NOT_FOUND);
            }
    
            return response()->json([
                'name'=> $user->name,
                'numero_compte' => $compte->numero_compte,
                
            ], Response::HTTP_OK);


        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error de recherche: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
}
