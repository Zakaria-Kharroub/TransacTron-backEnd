<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compte;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CompteController extends Controller
{
    //
    public function createCompte(Request $request){
        try {
            
            $existingCompte = Compte::where('user_id', Auth()->user()->id)
                        ->where('type', $request->type)
                        ->first();
        if ($existingCompte) {
            return response()->json([
                'message' =>'utilisateur possed deja compte de ce type',
            ], Response::HTTP_CONFLICT);
        }
    
            $compte= new Compte();
            $compte ->numero_compte = rand(1000000000, 9999999999); 
            $compte-> solde =$request->solde;
            $compte ->user_id= Auth()->user()->id;
            $compte ->type =$request->type;
            $compte -> save();
    
            return response()->json([
                'message' => 'compte created successfuly',
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message'=> 'error de creation de compte: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function searchCompte($name){
        try {
           $user= User::where('name', $name)->first();
            if (!$user) {
                return response()->json([
                    'message' => 'user not found',
               ], Response::HTTP_NOT_FOUND);
            }
    
             $comptes = Compte::where('user_id', $user->id)->get();
    
            if ($comptes->isEmpty()) {
                return response()->json([
                  'message' => 'ce user avez pas compte',
                ], Response::HTTP_NOT_FOUND);
            }
    
            $comptesArray = $comptes->map(function ($compte) use ($user) {
                return [
                    'name' =>$user->name,
                    'numero_compte'=> $compte->numero_compte,
                    'type'=> $compte->type,
                ];
            });
    
            return response()->json($comptesArray, Response::HTTP_OK);
    
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error de recherche: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function compteHistorique($numero_compte){
        try {
            $compte = Compte::where('numero_compte', $numero_compte)->first();  
           
            $transactions = Transaction::where('numero_compte_sender', $numero_compte)
                                        ->orWhere('numero_compte_receiver', $numero_compte)
                                        ->get();


            if (!$compte) {
                return response()->json([
                    'message' => 'compte not found',
                ], Response::HTTP_NOT_FOUND);
            }
    
    
            return response()->json([
              'transactions' => $transactions,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
             'message' => 'error de recherche: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
}
