<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfDeactivated
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
        $route=app()->router->getCurrentRoute()->uri();

        $route_as='';
        $route_action=$request->route()->getAction();
        if(isset($route_action['as'])) {
            $route_as=$route_action['as'];
        }

        if (Auth::check()){
            $user=Auth::user();
            if($user->status==4 && $route!='logout' && $route_as!='profil.editsettings' && $route_as!='profil.updatesettings')
            {
                return redirect('/profilom/beallitasok')->with('message',
                    'Amennyiben újból szeretnéd használni az oldalt, vedd ki a pipát a deaktiválásnál.');
            }
        }
        
        return $next($request);
    }
}
