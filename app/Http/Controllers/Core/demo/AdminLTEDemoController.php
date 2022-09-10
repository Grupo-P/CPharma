<?php

namespace App\Http\Controllers\core\demo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminLTEDemoController extends Controller
{
    public function index(){
        return view('core.LTEDemo.AdminLTEDemo');
    }
}
