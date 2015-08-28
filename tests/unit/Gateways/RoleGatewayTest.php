<?php namespace Gateways;

use Acoustep\EntrustGui\Gateways\RoleGateway;
use Acoustep\EntrustGui\Gateways\RoleRepository;
use \Mockery as m;

class RoleGatewayTest extends \Codeception\TestCase\Test
{

    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testInitialisation()
    {
      $config = m::mock('Illuminate\Config\Repository');
      $repository = m::mock('Acoustep\EntrustGui\Repositories\RoleRepository, Prettus\Repository\Eloquent\BaseRepository');
      $dispatcher = m::mock('Illuminate\Events\Dispatcher');
      $event_created_class = m::namedMock('Acoustep\EntrustGui\Events\RoleCreatedEvent', 'App\Events\Event');
      $tester = new RoleGateway($config, $repository, $dispatcher, $event_created_class);
      $this->assertInstanceOf(RoleGateway::class, $tester);
    }

    public function testCreate()
    {
      $data = [
          'name' => 'Admin',
          'display_name' => 'Admin',
          'description' => 'The Administrator',
          'permissions' => [1,2,3],
      ];

      $request = m::mock('Illuminate\Http\Request');
      $request->shouldReceive('all')->andReturn($data);
      $request->shouldReceive('get')->with('permissions', [])->andReturn($data['permissions']);

      $config = m::mock('Illuminate\Config\Repository');
      $repository = m::mock('Acoustep\EntrustGui\Repositories\RoleRepository, Prettus\Repository\Eloquent\BaseRepository');
      $repository->shouldReceive('create->perms->sync')->once();
      $dispatcher = m::mock('Illuminate\Events\Dispatcher');
      $event_created_class = m::namedMock('Acoustep\EntrustGui\Events\RoleCreatedEvent', 'App\Events\Event');
      $event_created_class->shouldReceive('setModel')->with(m::any());
      $dispatcher->shouldReceive('fire')->with(m::any());

      $tester = new RoleGateway($config, $repository, $dispatcher, $event_created_class);
      $result = $tester->create($request);
      $this->assertInternalType('object', $result);
    }

    public function testCreateThrowsExceptionWhenInvalid()
    {
    
    }

}
