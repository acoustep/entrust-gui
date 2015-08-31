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
    protected $event_created_class;
    protected $event_updated_class;
    protected $event_deleted_class;

    protected function _before()
    {
        $this->config = m::mock('Illuminate\Config\Repository');
        $this->repository = m::mock('Acoustep\EntrustGui\Repositories\RoleRepository, Prettus\Repository\Eloquent\BaseRepository');
        $this->dispatcher = m::mock('Illuminate\Events\Dispatcher');
        $this->dispatcher->shouldReceive('dispatch');
        $this->event_created_class = m::namedMock('Acoustep\EntrustGui\Events\RoleCreatedEvent', 'App\Events\Event');
        $this->event_updated_class = m::namedMock('Acoustep\EntrustGui\Events\RoleUpdatedEvent', 'App\Events\Event');
        $this->event_deleted_class = m::namedMock('Acoustep\EntrustGui\Events\RoleDeletedEvent', 'App\Events\Event');
    }

    protected function _after()
    {
    }

    /**
     * @test
     */
    public function initialisation()
    {
        $tester = new RoleGateway($this->config, $this->repository, $this->dispatcher, $this->event_created_class, $this->event_updated_class, $this->event_deleted_class);
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
        $this->event_created_class->shouldReceive('setModel')
              ->with(m::any());

        $tester = new RoleGateway($this->config, $this->repository, $this->dispatcher, $this->event_created_class, $this->event_updated_class, $this->event_deleted_class);
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
        $tester = new RoleGateway($this->config, $this->repository, $this->dispatcher, $this->event_created_class, $this->event_updated_class, $this->event_deleted_class);
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
        $this->event_updated_class->shouldReceive('setModel')
              ->with(m::any());

        $tester = new RoleGateway($this->config, $this->repository, $this->dispatcher, $this->event_created_class, $this->event_updated_class, $this->event_deleted_class);
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
        $this->event_deleted_class->shouldReceive('setModel')
              ->with(m::any());

        $tester = new RoleGateway($this->config, $this->repository, $this->dispatcher, $this->event_created_class, $this->event_updated_class, $this->event_deleted_class);
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
