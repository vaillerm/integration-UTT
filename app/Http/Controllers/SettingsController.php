<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Terbium\DbConfig\Facade as Config;

class SettingsController extends Controller
{
    public function getIndex()
    {
        return view('dashboard.configs.parameters', ['configs'=> $this->getConfigArray()]);
    }

    public function getEdit(Request $request, $settings_name)
    {
        if(!array_has($this->getConfigArray(),$settings_name)) {
            $request->session()->flash('error', "Le parametre n'existe pas.");
            return redirect()->route('dashboard.configs.parameters');
        }

        return view('dashboard.configs.parameter_edit', [
            'name'=> $settings_name
        ]);
    }
    public function postEdit(Request $request, $settings_name)
    {
        if(!array_has($this->getConfigArray(),$settings_name)) {
            $request->session()->flash('error', "Le parametre n'existe pas.");
            return redirect()->route('dashboard.configs.parameters');
        }

        Config::store($settings_name, $request->input('value'));
        $request->session()->flash('success', "Parametre mis à jour.");
        return redirect()->route('dashboard.configs.parameters');
    }

    private function getConfigArray()
    {
        $deny_access = [
            'app',
            'auth',
            'database'
            ];
        $config = Config::all();
        return array_dot(array_except($config, $deny_access));

    }

}
