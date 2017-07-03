<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Terbium\DbConfig\Facade as Config;

class SettingsController extends Controller
{
    public function getIndex()
    {
        return view('dashboard.configs.parameters');
    }

    public function getEdit(Request $request, $settings_name)
    {
        if(!Config::has($settings_name)) {
            $request->session()->flash('error', "Le parametre n'existe pas.");
            return redirect()->route('dashboard.configs.parameters');
        }

        return view('dashboard.configs.parameter_edit', [
            'name'=> $settings_name
        ]);
    }
    public function postEdit(Request $request, $settings_name)
    {
        if(!Config::has($settings_name)) {
            $request->session()->flash('error', "Le parametre n'existe pas.");
            return redirect()->route('dashboard.configs.parameters');
        }

        Config::store($settings_name, $request->input('value'));
        $request->session()->flash('success', "Parametre mis à jour.");
        return redirect()->route('dashboard.configs.parameters');
    }

}
