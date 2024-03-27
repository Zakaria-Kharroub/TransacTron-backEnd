<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_compte_sender',
        'numero_compte_receiver',
        'montant',
    ];

    public function compteSender(){
        return $this->belongsTo(Compte::class,'numero_compte_sender');
    }

    public function compteReceiver(){
        return $this->belongsTo(Compte::class, 'numero_compte_receiver');
    }

    



    


}
