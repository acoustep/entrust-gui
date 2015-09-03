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
    protected $config;
    protected $repository;
    protected $dispatcher;

    protected function _before()
    {
        $this->config = m::mock('Illuminate\Config\Repository');
        $this->repository = m::mock('Acoustep\EntrustGui\Repositories\RoleRepository, Prettus\Repository\Eloquent\BaseRepository');
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
        $tester = new RoleGateway($this->config, $this->repository, $this->dispatcher);
        $this->assertInstanceOf(RoleGateway::class, $tester);
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
            ->with('permissions', [])
            ->andReturn($data['permissions']);

        $this->repository->shouldReceive('create->perms->sync')
            ->once();
        $this->dispatcher->shouldReceive('fire')
              ->with(m::any());
        // $this->event_created_class->shouldReceive('setModel')
        //       ->with(m::any());
        $event = m::mock("overload:Acoustep\EntrustGui\Events\RoleCreatedEvent");
        $event->shouldReceive('setModel');

        $tester = new RoleGateway($this->config, $this->repository, $this->dispatcher);
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
        $tester = new RoleGateway($this->config, $this->repository, $this->dispatcher);
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
        $event = m::mock("overload:Acoustep\EntrustGui\Events\RoleUpdatedEvent");
        $event->shouldReceive('setModel');

        $tester = new RoleGateway($this->config, $this->repository, $this->dispatcher);
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
        $event = m::mock("overload:Acoustep\EntrustGui\Events\RoleDeletedEvent");
        $event->shouldReceive('setModel');

        $tester = new RoleGateway($this->config, $this->repository, $this->dispatcher);
        $result = $tester->delete($id);

    }

    protected function getData($override = [])
    {
        $data = [
            'name' => 'Admin',
            'display_name' => 'Admin',
            'description' => 'The Administrator',
            'permissions' => [1,2,3],
        ];

        return array_merge($data, $override);
    }

}
