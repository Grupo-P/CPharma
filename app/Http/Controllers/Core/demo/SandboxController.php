<?php

namespace App\Http\Controllers\core\demo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SandboxController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:core.demo.sandbox1')->only('sandbox1');
        $this->middleware('can:core.demo.sandbox2')->only('sandbox2');        
    }

    public function sandbox1(){
        return view('core.demo.sandbox1');
    }

    public function sandbox2(){
        return view('core.demo.sandbox2');
    }
}
