<?php
namespace Http\Middleware;

use Acoustep\EntrustGui\Http\Middleware\AdminAuth;
use \Mockery as m;

class AdminAuthTest extends \Codeception\TestCase\Test
{
    protected function _before()
    {
        $this->guard = m::mock('Illuminate\Contracts\Auth\Guard');
        $this->request = m::mock('Illuminate\Http\Request');
        $this->config = m::mock('Illuminate\Config\Repository');
        $this->response = m::mock('Illuminate\Http\Response');
        $this->redirect = m::mock('Illuminate\Routing\Redirector');
    }

    protected function _after()
    {
    }

    /**
     * @test
     */
    public function admin_can_access()
    {
        $this->guard->shouldReceive('guest')->andReturn(false);
        $this->request->shouldReceive('user->hasRole')->andReturn(true);
        $this->config->shouldReceive('get')->andReturn('admin');
        $next = function($request) {
          return true;
        };
        $tester = new AdminAuth($this->guard, $this->config, $this->response, $this->redirect);
        $result = $tester->handle($this->request, $next);
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function moderator_cant_access()
    {
        $this->guard->shouldReceive('guest')->andReturn(false);
        $this->request->shouldReceive('user->hasRole')->andReturn(false);
        $this->config->shouldReceive('get')->andReturn('admin');
        $this->response->shouldReceive('make')->with('Unauthorized.', 401);
        $next = function($request) {
          return true;
        };
        $tester = new AdminAuth($this->guard, $this->config, $this->response, $this->redirect);
        $result = $tester->handle($this->request, $next);
        $this->assertNotTrue($result);
    }

    /**
     * @test
     */
    public function guest_cant_access_with_ajax()
    {
        $this->guard->shouldReceive('guest')->andReturn(true);
        $this->request->shouldReceive('ajax')->andReturn(true);
        $this->response->shouldReceive('make')->with('Unauthorized.', 401);
        $next = function($request) {
          return true;
        };
        $tester = new AdminAuth($this->guard, $this->config, $this->response, $this->redirect);
        $result = $tester->handle($this->request, $next);
        $this->assertNotTrue($result);
    }

    /**
     * @test
     */
    public function guest_cant_access()
    {
        $this->guard->shouldReceive('guest')->andReturn(true);
        $this->request->shouldReceive('ajax')->andReturn(false);
        $this->redirect->shouldReceive('guest')->with('auth/login');
        $next = function($request) {
          return true;
        };
        $tester = new AdminAuth($this->guard, $this->config, $this->response, $this->redirect);
        $result = $tester->handle($this->request, $next);
        $this->assertNotTrue($result);
    }

}
