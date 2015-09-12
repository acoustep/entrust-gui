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
    protected $root;

    public function setUp()
    {
        parent::setUp();
        $this->root = vfsStream::setup();
    }

    /**
     * @test
     */
    public function generate_with_default_params()
    {
        $command = $this->artisan('entrust-gui:models', [
          '--path' => $this->root->url(),
          '--force' => 'true',
        ]);
        $this->assertTrue($this->root->hasChild('User.php'));
        $this->assertTrue($this->root->hasChild('Permission.php'));
        $this->assertTrue($this->root->hasChild('Role.php'));
    }

    /**
     * @test
     */
    public function generate_specific_model()
    {
        $command = $this->artisan('entrust-gui:models', [
          'model' => 'User',
          '--path' => $this->root->url(),
          '--force' => 'true',
        ]);
        $this->assertTrue($this->root->hasChild('User.php'));
        $this->assertFalse($this->root->hasChild('Permission.php'));
        $this->assertFalse($this->root->hasChild('Role.php'));
    }

    /**
     * @test
     */
    public function generate_specific_path()
    {
        $command = $this->artisan('entrust-gui:models', [
          '--path' => $this->root->url().'/models',
          '--force' => 'true',
        ]);
        $this->assertTrue($this->root->hasChild('models/User.php'));
        $this->assertTrue($this->root->hasChild('models/Permission.php'));
        $this->assertTrue($this->root->hasChild('models/Role.php'));
    }

}
