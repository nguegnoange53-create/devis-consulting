<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'proforma_id', 
        'designation',   // ex: NVR 32ports [cite: 19]
        'quantite',      // ex: 1 [cite: 19]
        'prix_unitaire'  // ex: 350000 [cite: 19]
    ];

    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }
}
