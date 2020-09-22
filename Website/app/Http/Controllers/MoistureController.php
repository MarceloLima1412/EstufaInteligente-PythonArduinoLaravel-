<?php

namespace App\Http\Controllers;

use App\Moisture;
use Illuminate\Http\Request;

class MoistureController extends Controller
{
    public function index()
    {
      
        $moistures = Moisture::orderBy('data', 'desc')
        ->paginate(14)->appends('sort', request('sort'));
        return view('moistures.index', compact('moistures'));
    }

    


}