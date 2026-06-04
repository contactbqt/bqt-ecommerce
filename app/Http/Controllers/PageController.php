<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Department;

class PageController extends Controller
{ 

    public function index()
    {
        return redirect()->route('home');
    }

    public function appointment_success()
    {
        return view('appointment-success');
    }



    


}
