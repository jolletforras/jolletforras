<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfIncompleteProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$user=Auth::user();

		if (Auth::check() && !$this->exceptionRoute($request)) {
			// már meg van erősítve az email cím
			if($user->status==1) {

				if($user->incompleteProfile())
				{
					$request->session()->forget('warning');

					$message='';

					$request->session()->flash('warning', true);

					$message.="
					Kérlek, írj itt magadról!<br> 
                    A bemutatkozásoddal megajándékozod bizalmaddal a többieket, ahogyan Te is elolvashatod mások bemutatkozásait.<br>
                    Ne aggódj: az adatlapodat bármikor módosíthatod. <br>
                    A csillaggal (*) jelölt mezők kitöltése szükséges. <br>
                    Ha az adatlap végén azt választod ki, hogy nyilvános a bemutatkozásod, akkor nem regisztráltak számára is láthatóvá válik. Alapesetben csak regisztráltak számára látható az adatlapod.";

					return redirect()->route('profil.edit')->with('message',$message);
				}

				if (!$user->has_photo) {
					$request->session()->flash('help', true);
					return redirect('/profilom/feltolt_profilkep')->with('message',
						'Minden szükséges adatot megadtál, már csak kép feltöltése szükséges az oldal használatához.');
				}
			}
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

		$exception_route_as = ['profil.edit', 'profil.update', 'profil.uploadimage', 'profil.saveimage'];

		return $route=='logout' ||	in_array($route_as, $exception_route_as);

	}
}
