<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
           $this->middleware('auth');
        
       
          //  $this->middleware('TokenCheckMiddleware');      
       
        
    }

    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // $sessionID=session()->get("access_token");

        // $userID=Auth::id();

       // return $userID;

       
       return view('home');
    }
}
