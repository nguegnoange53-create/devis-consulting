<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentLigne extends Model
{
    protected $fillable = ['document_id', 'designation', 'quantite', 'prix_unitaire'];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
