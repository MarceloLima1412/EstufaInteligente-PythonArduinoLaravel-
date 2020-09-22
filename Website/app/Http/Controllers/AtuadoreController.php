<?php

namespace App\Http\Controllers;

use App\Atuadore;
use Illuminate\Http\Request;

class AtuadoreController extends Controller
{
    public function index()
    {
        $atuadores = Atuadore::paginate(3);
        return view('atuadore.index', compact('atuadores'));
    }

    public function ativar(Atuadore $atuadore)
    {
        if(request()->filled('ativo')){
            $atuadore->ativo=request()->ativo;
            
        }
        if($atuadore->ativo == 0){
            $atuadore->ativo = 1;
        }else{
            $atuadore->ativo = 0;
        }

        $atuadore->update();

        return redirect()
        ->route("atuadore.index");
    }


}
