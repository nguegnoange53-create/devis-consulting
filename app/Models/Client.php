<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['user_id', 'raison_sociale', 'adresse', 'telephone', 'email', 'rccm_cc', 'logo'];

    protected static function booted()
    {
        static::addGlobalScope('user', function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (auth()->check()) {
                $builder->where('user_id', auth()->id());
            }
        });
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
