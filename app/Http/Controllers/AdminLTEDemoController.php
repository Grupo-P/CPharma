<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminLTEDemoController extends Controller
{
    public function index(){
        return view('AdminLTEDemo');
    }
}
