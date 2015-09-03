<?php namespace Gateways;

use Acoustep\EntrustGui\Gateways\PermissionGateway;
use Acoustep\EntrustGui\Gateways\PermissionRepository;
use \Mockery as m;

class PermissionGatewayTest extends \Codeception\TestCase\Test
{

    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $config;
    protected $repository;
    protected $dispatcher;

    protected function _before()
    {
        $this->config = m::mock('Illuminate\Config\Repository');
        $this->repository = m::mock('Acoustep\EntrustGui\Repositories\PermissionRepository, Prettus\Repository\Eloquent\BaseRepository');
        $this->dispatcher = m::mock('Illuminate\Events\Dispatcher');
        $this->dispatcher->shouldReceive('dispatch');
    }

    protected function _after()
    {
    }

    /**
     * @test
     */
    public function initialisation()
    {
        $tester = new PermissionGateway($this->config, $this->repository, $this->dispatcher);
        $this->assertInstanceOf(PermissionGateway::class, $tester);
    }

    /**
     * @test
     */
    public function create()
    {
        $data = $this->getData();

        $request = m::mock('Illuminate\Http\Request');
        $request->shouldReceive('all')
            ->andReturn($data);
        $request->shouldReceive('get')
            ->with('roles', [])
            ->andReturn($data['roles']);

        $this->repository->shouldReceive('create->roles->sync')
            ->once();
        $this->dispatcher->shouldReceive('fire')
              ->with(m::any());
        $event = m::mock("overload:Acoustep\EntrustGui\Events\PermissionCreatedEvent");
        $event->shouldReceive('setModel');

        $tester = new PermissionGateway($this->config, $this->repository, $this->dispatcher);
        $result = $tester->create($request);
        $this->assertInternalType('object', $result);
    }
    
    /**
     * @test
     */
    public function find()
    {
        $id = 1;
        $repository = $this->repository;
        $repository->shouldReceive('with->find');
        $tester = new PermissionGateway($this->config, $this->repository, $this->dispatcher);
        $result = $tester->find($id);
    }

    /**
     * @test
     */
    public function update()
    {
        $data = $this->getData();
        $id = 1;

        $request = m::mock('Illuminate\Http\Request');
        $request->shouldReceive('all')
            ->andReturn($data);

        $this->repository->shouldReceive('update')->andReturn($data);

        $this->dispatcher->shouldReceive('fire')
              ->with(m::any());
        $event = m::mock("overload:Acoustep\EntrustGui\Events\PermissionUpdatedEvent");
        $event->shouldReceive('setModel');

        $tester = new PermissionGateway($this->config, $this->repository, $this->dispatcher);
        $result = $tester->update($request, $id);
    }

    /**
     * @test
     */
    public function delete()
    {
        $id = 1;
        $data = $this->getData();

        $this->repository->shouldReceive('find')->andReturn($data);
        $this->repository->shouldReceive('delete')->with($id);

        $this->dispatcher->shouldReceive('fire')
              ->with(m::any());
        $event = m::mock("overload:Acoustep\EntrustGui\Events\PermissionDeletedEvent");
        $event->shouldReceive('setModel');

        $tester = new PermissionGateway($this->config, $this->repository, $this->dispatcher);
        $result = $tester->delete($id);

    
    }

    protected function getData($override = [])
    {
        $data = [
            'name' => 'create-user',
            'display_name' => 'Create User',
            'description' => 'Ability to create a user',
            'roles' => [1,2,3],
        ];

        return array_merge($data, $override);
    }

}
