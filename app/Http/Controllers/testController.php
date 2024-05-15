<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class testController extends Controller
{
    private $test=null;
    public function __construct(){
        $this->test=100;
    }
    public function create(){
        dd($this->test);
    }
}
