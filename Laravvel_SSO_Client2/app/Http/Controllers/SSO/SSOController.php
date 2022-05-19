<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Auth;
use Laravel\Passport\HasApiTokens;
use DB;


class SSOController extends Controller
{


    public function getLogin(Request $request)
    {
        $request->session()->put("state",$state=Str::random(40));

       $query=http_build_query([

        "client_id"=>"95e40257-1bb6-4856-84d2-3585917dc43e",

        "redirect_uri"=>"http://127.0.0.1:9090/callback",

        "response_type"=>"code",

        "scope"=>"view-user",

        "state"=>$state
    ]);

        return redirect("http://127.0.0.1:8000/oauth/authorize?".$query);
    }


    public function getCallback(Request $request)
    {
        $state= $request->session()->pull("state");

    throw_unless(strlen($state) > 0 && $state === $request->state,
    
    InvalidArgumentException::class);

    $response=Http::asForm()->post(
        
        "http://127.0.0.1:8000/oauth/token",
        
        [

        "grant_type"=>"authorization_code",

        "client_id"=>"9656c0df-d58a-45f3-89c3-992195070a9c",

        "client_secret"=>"2tF9M7PrZivD6YYfOmfleWT6PY82pBjHjZQQAl1N",

        "redirect_uri"=>"http://127.0.0.1:9090/callback",

        "code"=> $request->code
    ]);
   

    $request->session()->put($response->json());
    return redirect(route("sso.connect"));


    }

    public function connectUser(Request $request)
    {
        $access_token=$request->session()->get("access_token");
    
        $response=Http::withHeaders([


            "Accept"=>"application/json",

            "Authorization"=>"Bearer ".$access_token


        ])->get("http://127.0.0.1:8000/api/user");

         $userArray= $response->json();

         try {
            
            $email = $userArray['email'];
            
         } catch (\Throwable $th) {

            return redirect("login")->withError("Failed to get login information ! try again");
         }


         $user=User::where('email',$email)->first();

        //  if (!$user) {
             
        //     $user = new User;

        //     $user->name=$userArray['name'];
        //     $user->email=$userArray['email'];
        //     $user->email_verified_at=$userArray['email_verified_at'];
        //     $user->save();

        //  }

       
            Auth::login($user);
        
         return redirect(route('home'));

        // return redirect(route('home',[

        //     'user'=> $user
        // ]));
    }


    public function logoutApi(Request $request)
    {
       $access_token=$request->session()->get("access_token");

        if (auth()->user()->token) {
            
            $response=Http::withHeaders([


                "Accept"=>"application/json",
    
                "Authorization"=>"Bearer ".auth()->user()->token->access_token
    
    
            ])->get("http://127.0.0.1:8000/api/logout");


          return  $response->json();
        }

        
        


    }
    

}
