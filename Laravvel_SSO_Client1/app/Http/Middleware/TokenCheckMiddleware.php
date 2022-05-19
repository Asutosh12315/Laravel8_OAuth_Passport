<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OauthAccessToken;
use Auth;


class TokenCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $userID=Auth::id();
        //$sessionUserID=session()->get("user_id");

      $loggedUser=OauthAccessToken::where('user_id',$userID)->first();


      if ($loggedUser) {
          
        return $next($request);
      }
      else {
         
     
       return redirect('/');


      }

     
    }
}
