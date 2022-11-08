<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotActivated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user=Auth::user();

        if (Auth::check() && $user->status==0)
        {
            $status_msg ='Köszönjük a regisztrációdat. Email címedre levelet küldtünk, amelyen keresztül aktiválhatod a regisztrációdat. (Amennyiben nem találod,
                   kérlek nézd meg a levélszemét/spam mappában is.)';
            $request->session()->flash('status_msg', $status_msg);

            Auth::logout();

            return redirect('/');

        }
        
        return $next($request);
    }

    private function exceptionRoute($request) {
        $route=app()->router->getCurrentRoute()->uri();

        $route_as='';
        $route_action=$request->route()->getAction();
        if(isset($route_action['as'])) {
            $route_as=$route_action['as'];
        }

        $exception_route_as = ['profil.edit', 'profil.update', 'profil.uploadimage', 'profil.saveimage', 'profile.show', 'profil.savepassword'];

        return $route=='logout' || $route=='profilom/jelszocsere' || in_array($route_as, $exception_route_as);

    }
}
