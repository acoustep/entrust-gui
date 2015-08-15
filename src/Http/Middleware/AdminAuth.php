<?php namespace Acoustep\EntrustGui\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Config;

/**
 * This file is part of Entrust GUI,
 * A Laravel 5 GUI for Entrust.
 *
 * From http://stackoverflow.com/a/29186175
 *
 * @license MIT
 * @package Acoustep\EntrustGui
 */
class AdminAuth
{

    protected $auth;

    /**
     * Create a new AdminAuth instance.
     *
     * @param Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle the request
     *
     * @param mixed $request
     * @param Closure $next
     *
     * @return Response
     */
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
