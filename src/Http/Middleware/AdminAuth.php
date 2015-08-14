<?php namespace Acoustep\EntrustGui\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Config;

// From http://stackoverflow.com/a/29186175

class AdminAuth
{

    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        } elseif (! $request->user()->hasRole(Config::get('entrust-gui.middleware-role'))) {
            return response('Unauthorized.', 401); //Or redirect() or whatever you want
        }
        return $next($request);
    }
}
