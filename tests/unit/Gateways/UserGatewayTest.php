<?php namespace Gateways;

use Acoustep\EntrustGui\Gateways\UserGateway;
use Acoustep\EntrustGui\Gateways\UserRepository;
use \Mockery as m;


class RoleDummy
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

    protected function _before()
    {
        $this->config = m::mock('Illuminate\Config\Repository');
        $this->config->shouldReceive('get')->andReturn('\Gateways\RoleDummy');
        $this->repository = m::mock('Acoustep\EntrustGui\Repositories\UserRepository, Prettus\Repository\Eloquent\BaseRepository');
        $this->dispatcher = m::mock('Illuminate\Events\Dispatcher');
        $this->dispatcher->shouldReceive('dispatch');
        $this->hash = m::mock('Illuminate\Contracts\Hashing\Hasher');
    }

    protected function _after()
    {
    }

    /**
     * @test
     */
    public function initialisation()
    {
        $tester = new UserGateway($this->config, $this->repository, $this->dispatcher, $this->hash);
        $this->assertInstanceOf(UserGateway::class, $tester);
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
        // $request->shouldReceive('get')
        //     ->with('password', '')->andReturn($data['password']);
        $request->shouldReceive('get')
            ->with('roles', [])
            ->andReturn($data['roles']);

        $this->hash->shouldReceive('make');

        $this->repository->shouldReceive('create');

        $this->dispatcher->shouldReceive('fire')
              ->with(m::any());
        $event = m::mock("overload:Acoustep\EntrustGui\Events\UserCreatedEvent");
        $event->shouldReceive('setModel');

        $tester = new UserGateway($this->config, $this->repository, $this->dispatcher, $this->hash);
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
        $request->shouldReceive('has')
            ->with('password')->andReturn(true);
        $request->shouldReceive('get')
            ->with('password', false)->andReturn($data['password']);
        $request->shouldReceive('get')
            ->with('password')->andReturn($data['password']);
        $request->shouldReceive('get')
            ->with('password_confirmation')->andReturn($data['password']);
        $request->shouldReceive('get')
            ->with('roles', [])
            ->andReturn($data['roles']);

        $this->hash->shouldReceive('make');

        $this->repository->shouldReceive('update');

        $this->dispatcher->shouldReceive('fire')
              ->with(m::any());
        $event = m::mock("overload:Acoustep\EntrustGui\Events\UserUpdatedEvent");
        $event->shouldReceive('setModel');

        $tester = new UserGateway($this->config, $this->repository, $this->dispatcher, $this->hash);
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
        $event = m::mock("overload:Acoustep\EntrustGui\Events\UserDeletedEvent");
        $event->shouldReceive('setModel');

        $tester = new UserGateway($this->config, $this->repository, $this->dispatcher, $this->hash);
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
        $tester = new UserGateway($this->config, $this->repository, $this->dispatcher, $this->hash);
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
