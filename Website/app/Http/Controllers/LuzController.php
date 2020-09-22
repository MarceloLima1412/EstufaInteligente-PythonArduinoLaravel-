<?php

namespace App\Http\Controllers;

use App\Luz;
use Illuminate\Http\Request;

class LuzController extends Controller
{
    public function index()
    {
        $luzes = Luz::orderBy('data', 'desc')
        ->paginate(14)->appends('sort', request('sort'));

        return view('luz.index', compact('luzes'));
    }


}