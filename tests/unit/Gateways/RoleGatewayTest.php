<?php
namespace Gateways;

use Acoustep\EntrustGui\Gateways\RoleGateway;
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
      // $repository->getMock('');
      $dispatcher = m::mock('Illuminate\Events\Dispatcher');
      $gateway = new RoleGateway($config, $repository, $dispatcher);
      $this->assertInstanceOf(RoleGateway::class, $gateway);
    }

}
