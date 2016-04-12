<?php namespace Acoustep\EntrustGui\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

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
    protected $config;
    protected $response;
    protected $redirect;

    /**
     * Create a new AdminAuth instance.
     *
     * @param Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth, Config $config, Response $response, Redirector $redirect)
    {
        $this->auth = $auth;
        $this->config = $config;
        $this->response = $response;
        $this->redirect = $redirect;
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
                abort(401, 'Unauthenticated action');
            } else {
                return $this->redirect->guest($this->config->get('entrust-gui.unauthorized-url', 'auth/login'));
            }
        } elseif (! $request->user()->hasRole($this->config->get('entrust-gui.middleware-role'))) {
            abort(403, 'Unauthorized action'); //Or redirect() or whatever you want
        }
        return $next($request);
    }
}
