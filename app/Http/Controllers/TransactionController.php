<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Compte;
use App\Models\Transaction;

class TransactionController extends Controller
{
    //

    public function createTransaction(Request $request){
        try {
            $compteSender =Compte::where('numero_compte', $request->numero_compte_sender)->first();
             $compteReceiver= Compte::where('numero_compte', $request->numero_compte_receiver)->first();
            if (!$compteSender || !$compteReceiver) {
                return response()->json([
                    'message' => 'compte not found',
                ], Response::HTTP_NOT_FOUND);
            }
            if ($compteSender->solde < $request->montant) {
                return response()->json([
                    'message' => 'solde insuffisant',
                ], Response::HTTP_BAD_REQUEST);
            }
            $compteSender->solde -= $request->montant;
            $compteSender->save();
            $compteReceiver->solde += $request->montant;
            $compteReceiver->save();
            $transaction = new Transaction();
            $transaction->numero_compte_sender = $request->numero_compte_sender;
            $transaction->numero_compte_receiver = $request->numero_compte_receiver;
            $transaction->montant = $request->montant;
            $transaction->save();
            return response()->json([
                'message' => 'transaction created successfully',
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error de transaction: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    

}
