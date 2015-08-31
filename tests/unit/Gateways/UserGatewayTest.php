<?php namespace Gateways;

use Acoustep\EntrustGui\Gateways\UserGateway;
use Acoustep\EntrustGui\Gateways\UserRepository;
use \Mockery as m;


class RoleStub
{

}

class UserGatewayTest extends \Codeception\TestCase\Test
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
        $this->config->shouldReceive('get')->andReturn('\Gateways\RoleStub');
        $this->repository = m::mock('Acoustep\EntrustGui\Repositories\UserRepository, Prettus\Repository\Eloquent\BaseRepository');
        $this->dispatcher = m::mock('Illuminate\Events\Dispatcher');
        $this->dispatcher->shouldReceive('dispatch');
        $this->hash = m::mock('Illuminate\Contracts\Hashing\Hasher');
        $this->event_created_class = m::namedMock('Acoustep\EntrustGui\Events\UserCreatedEvent', 'App\Events\Event');
        $this->event_updated_class = m::namedMock('Acoustep\EntrustGui\Events\UserUpdatedEvent', 'App\Events\Event');
        $this->event_deleted_class = m::namedMock('Acoustep\EntrustGui\Events\UserDeletedEvent', 'App\Events\Event');
    }

    protected function _after()
    {
    }

    /**
     * @test
     */
    public function initialisation()
    {
        $tester = new UserGateway($this->config, $this->repository, $this->dispatcher, $this->hash, $this->event_created_class, $this->event_updated_class, $this->event_deleted_class);
        $this->assertInstanceOf(UserGateway::class, $tester);
    }

    /**
     * @test
     */
    public function create()
    {
        $data = $this->getData();
        $request = m::mock('Illuminate\Http\Request');
        $request->shouldReceive('except')
            ->andReturn($data);
        $request->shouldReceive('get')
            ->with('password', '')->andReturn($data['password']);
        $request->shouldReceive('get')
            ->with('roles', [])
            ->andReturn($data['roles']);

        $this->hash->shouldReceive('make');

        $this->repository->shouldReceive('create');

        $this->dispatcher->shouldReceive('fire')
              ->with(m::any());
        $this->event_created_class->shouldReceive('setModel')
              ->with(m::any());

        $tester = new UserGateway($this->config, $this->repository, $this->dispatcher, $this->hash, $this->event_created_class, $this->event_updated_class, $this->event_deleted_class);
        $tester->create($request);
    }

    /**
     * @test
     */
    public function update()
    {
        $data = $this->getData();
        $id = 1;
        $request = m::mock('Illuminate\Http\Request');
        $request->shouldReceive('except')
            ->andReturn($data);
        $request->shouldReceive('get')
            ->with('password', false)->andReturn($data['password']);
        $request->shouldReceive('get')
            ->with('password')->andReturn($data['password']);
        $request->shouldReceive('get')
            ->with('roles', [])
            ->andReturn($data['roles']);

        $this->hash->shouldReceive('make');

        $this->repository->shouldReceive('update');

        $this->dispatcher->shouldReceive('fire')
              ->with(m::any());
        $this->event_updated_class->shouldReceive('setModel')
              ->with(m::any());

        $tester = new UserGateway($this->config, $this->repository, $this->dispatcher, $this->hash, $this->event_created_class, $this->event_updated_class, $this->event_deleted_class);
        $tester->update($request, $id);
    }


    /**
     * @test
     */
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

        $tester = new UserGateway($this->config, $this->repository, $this->dispatcher, $this->hash, $this->event_created_class, $this->event_updated_class, $this->event_deleted_class);
        $result = $tester->delete($id);

    }


    /**
     * @test
     */
    public function find()
    {
        $id = 1;
        $repository = $this->repository;
        $repository->shouldReceive('with->find');
        $tester = new UserGateway($this->config, $this->repository, $this->dispatcher, $this->hash, $this->event_created_class, $this->event_updated_class, $this->event_deleted_class);
        $result = $tester->find($id);
    }





    protected function getData($override = [])
    {
        $data = [
            'id' => null,
            'email' => 'email@email.com',
            'password' => 'hunter2',
            'roles' => [1,2,3],
        ];

        return array_merge($data, $override);
    }

}
