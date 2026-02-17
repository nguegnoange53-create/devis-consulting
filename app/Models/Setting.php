<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['nom_entreprise', 'logo', 'cachet', 'adresse', 'telephone', 'email', 'site_web', 'rccm_cc', 'tva_defaut', 'devise'];
}
