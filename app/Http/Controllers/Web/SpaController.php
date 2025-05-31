<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SpaController extends Controller
{
        public function index()
    {
        // This tells Laravel to load the 'app.blade.php' file
        // from the 'resources/views/' directory.
        return view('app');
    }
}
