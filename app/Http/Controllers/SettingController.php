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
        
        $data = $request->only(['nom_entreprise', 'adresse', 'telephone', 'email', 'rccm_cc', 'tva_defaut', 'devise']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        if ($request->hasFile('cachet')) {
            $data['cachet'] = $request->file('cachet')->store('cachets', 'public');
        }

        $settings->fill($data);
        $settings->save();

        return back()->with('success', 'Paramètres mis à jour !');
    }
}
