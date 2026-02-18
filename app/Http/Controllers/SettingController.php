<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = \App\Models\Setting::first();
        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = \App\Models\Setting::first() ?? new \App\Models\Setting();
        
        $data = $request->only(['nom_entreprise', 'adresse', 'telephone', 'email', 'rccm_cc', 'tva_defaut', 'devise', 'site_web']);

        // Traiter le logo - ne mettre à jour que s'il y a un nouveau fichier
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        } else {
            // Préserver le logo existant
            $data['logo'] = $settings->logo;
        }

        // Traiter le cachet - ne mettre à jour que s'il y a un nouveau fichier
        if ($request->hasFile('cachet')) {
            $data['cachet'] = $request->file('cachet')->store('cachets', 'public');
        } else {
            // Préserver le cachet existant
            $data['cachet'] = $settings->cachet;
        }

        $settings->fill($data);
        $settings->save();

        return back()->with('success', 'Paramètres mis à jour !');
    }
}
