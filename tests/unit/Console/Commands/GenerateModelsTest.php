<?php
namespace Console\Commands;

use Acoustep\EntrustGui\Console\Commands\GenerateModels;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use \Mockery as m;
use Acoustep\EntrustGui\Tests\TestCase;
use org\bovigo\vfs\vfsStream;

class GenerateModelsTest extends TestCase
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @test
     */
    public function generate_default()
    {
        $root = vfsStream::setup();
        $command = $this->artisan('entrust-gui:models', [
          '--path' => $root->url(),
          '--force' => 'true',
        ]);
        $this->assertTrue($root->hasChild('User.php'));
        $this->assertTrue($root->hasChild('Permission.php'));
        $this->assertTrue($root->hasChild('Role.php'));
    }

}
