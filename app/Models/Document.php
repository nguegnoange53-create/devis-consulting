<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['user_id', 'type', 'numero', 'client_id', 'date_emission', 'objet', 'lieu', 'titre_document', 'taux_tva', 'statut', 'total_ht', 'total_tva', 'total_ttc'];

    protected static function booted()
    {
        static::addGlobalScope('user', function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (auth()->check()) {
                $builder->where('user_id', auth()->id());
            }
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lignes()
    {
        return $this->hasMany(DocumentLigne::class);
    }
}
