<?php
namespace Console\Commands;

use Acoustep\EntrustGui\Console\Commands\GenerateModels;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use \Mockery as m;

class GenerateModelsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    // protected function _before()
    // {
    // }
    //
    // protected function _after()
    // {
    // }

    /**
     * @test
     */
    public function initialisation()
    {
        $tester = new GenerateModels;
        $this->assertInstanceOf(GenerateModels::class, $tester);
    }

    /**
     * @test
     */
    public function generating_all()
    {
        $kernel = m::mock('Symfony\Component\HttpKernel\Kernel');
        $application = new Application($kernel);
        $tester = new GenerateModels;
        $application->add($tester);
        $command = $application->find('entrust-gui:models');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
    }

}
