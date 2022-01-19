<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class HomeController extends Controller
{
    
    public function index(Request $request)
    {
        return view("front.home");
    }
    
    public function redirectUser()
    {
        if (auth()->user()->role == 'admin') {
            return redirect('/admin/dashboard');
        }else
        {
            return redirect()->route('home');
        }
    }

}
