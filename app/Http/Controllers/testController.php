<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Testing\MimeType;

class testController extends Controller
{
    public function mime(){
        return MimeType::get('jpg');
    }
}
