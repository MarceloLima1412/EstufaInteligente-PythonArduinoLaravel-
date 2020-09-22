<?php

namespace App\Http\Controllers;

use App\Tempehumid;
use Illuminate\Http\Request;

class TempehumidController extends Controller
{
    public function index()
    {
        $tempehumids = Tempehumid::orderBy('data', 'desc')
        ->paginate(14)->appends('sort', request('sort'));

        return view('tempehumid.index', compact('tempehumids'));
    }


}
