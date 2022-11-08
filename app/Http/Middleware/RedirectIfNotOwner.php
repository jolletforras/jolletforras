<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Forum;

//nem használom, mivel egyenként kell kezelnem és rákeresnem és ez egy plusz rákeresés, pedig csak ritkán lesz ilyen eset
class RedirectIfNotOwner
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
        $route_action=$request->route()->getAction();
        if(isset($route_action['as'])) {
            $route_as=$route_action['as'];
            switch ($route_as) {
                case 'forum.edit':
                    $forum = Forum::findOrFail($request->id);
                    $user_id=$forum->user->id;
                    break;
            }

            if(isset($user_id) && Auth::user()->id != $user_id)
            {
                return redirect('/');
            }
        }

        return $next($request);
    }
}
