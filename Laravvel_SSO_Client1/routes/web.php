<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view("welcome");
});

Route::get("/sso/login",[App\Http\Controllers\SSO\SSOController::class,'getLogin'])->name('sso.login');

Route::get("/callback",[App\Http\Controllers\SSO\SSOController::class,'getCallback'])->name('sso.callback');

Route::get("/sso/connect",[App\Http\Controllers\SSO\SSOController::class,'connectUser'])->name('sso.connect');



Auth::routes(['register' => false , 'reset' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');




// Route::get("/login",function (Request $request)
// {
    
//     $request->session()->put("state",$state=Str::random(40));

//     $query=http_build_query([

//         "client_id"=>"95cfaacd-cb3c-47a5-93ce-6e3fc98c719d",

//         "redirect_uri"=>"http://127.0.0.1:8080/callback",

//         "response_type"=>"code",

//         "scope"=>"view-user",

//         "state"=>$state
//     ]);

//         return redirect("http://127.0.0.1:8000/oauth/authorize?".$query);

// });


// Route::get("/callback",function(Request $request){

//     $state= $request->session()->pull("state");

//     throw_unless(strlen($state) > 0 && $state === $request->state,
    
//     InvalidArgumentException::class);

//     $response=Http::asForm()->post(
        
//         "http://127.0.0.1:8000/oauth/token",
        
//         [

//         "grant_type"=>"authorization_code",

//         "client_id"=>"95cfaacd-cb3c-47a5-93ce-6e3fc98c719d",

//         "client_secret"=>"mBk9VjwaZczhabnrQZ8VRSirddrDFEhQVmbEAE78",

//         "redirect_uri"=>"http://127.0.0.1:8080/callback",

//         "code"=> $request->code
//     ]);

//     $request->session()->put($response->json());
//     return redirect("/authuser");
// });


// Route::get("/authuser",function (Request $request)
// {
//     $access_token=$request->session()->get("access_token");
    
//     $response=Http::withHeaders([


//         "Accept"=>"application/json",

//         "Authorization"=>"Bearer ".$access_token


//     ])->get("http://127.0.0.1:8000/api/user");

//    // return view('User',compact('response'));

//    return $response->json();
    
// });


// Route::get("/logout",function ()
// {
//     if (session()->has('user')) {
        
//         session()->pull('user');

//         return redirect("/");
//     }
// });

