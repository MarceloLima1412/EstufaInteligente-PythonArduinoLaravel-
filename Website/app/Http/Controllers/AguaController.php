<?php

namespace App\Http\Controllers;

use App\Agua;
use Illuminate\Http\Request;

class AguaController extends Controller
{
    public function index()
    {
        $aguas = Agua::orderBy('data', 'desc')
        ->paginate(14)->appends('sort', request('sort'));

        return view('agua.index', compact('aguas'));
    }


}
