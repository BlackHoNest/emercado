<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /*
     * Dashboard Pages Routs
     */
    public function index(Request $request)
    {
        $assets = ['chart', 'animation'];
        $uType = Auth::user()->user_type;

        if (strtolower($uType) == "seller"){
            return view('dashboards.seller', compact('assets'));
        }elseif (strtolower($uType) == "buyer"){

        }else{

        }
       
    }


    /*
     * uisheet Page Routs
     */
    public function guest(Request $request)
    {
        return view('guest');
    }



    /*
     * Auth Routs
     */
    public function signin(Request $request)
    {
        return view('auth.login');
    }

}
