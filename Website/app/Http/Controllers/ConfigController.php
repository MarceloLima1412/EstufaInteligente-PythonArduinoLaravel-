<?php

namespace App\Http\Controllers;

use App\Config;
use Illuminate\Http\Request;
use Hash;

class ConfigController extends Controller
{
    public function index()
    {
        $configs = Config::all();
        return view('config.index', compact('configs'));
    }

    public function variavel(Config $config)
    {
        if(request()->filled('variavel')){
            $config->variavel=request()->variavel;
            
        }
        if($config->variavel == 0){
            $config->variavel = 1;
        }else{
            $config->variavel = 0;
        }

        $config->update();

        return redirect()
        ->route("config.index");
    }


}