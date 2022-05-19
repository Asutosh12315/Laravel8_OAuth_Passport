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

        "client_id"=>"95e5fba7-eb5a-4638-800b-268f5ca6ccdd",

        "redirect_uri"=>"http://127.0.0.1:8080/callback",

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

        "client_id"=>"9656c04e-3ba3-42c1-bbee-53fcb2602190",

        "client_secret"=>"x9ehv1dmG4i2BEtPXMYctb2uFkVYseW5lADJLAJi",

        "redirect_uri"=>"http://127.0.0.1:8080/callback",

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
