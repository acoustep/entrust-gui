<?php namespace Acoustep\EntrustGui\Tests\Unit\Http;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Acoustep\EntrustGui\Tests\TestCase;

/**
 * Class     RoutesTest
 *
 * @package  Arcanedev\LogViewer\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @todo:    Find a way to test the route Classes with testbench (Find another tool if it's impossible).
 */
class RoutesTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => __DIR__.'/../../../database/migrations',
        ]);
    }
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */

    /** 
     * @test 
     * */
    public function it_can_see_users_index_page()
    {
        $response = $this->route('GET', 'entrust-gui::users.index');

        $this->assertResponseOk();
        $this->assertContains(
            '<h1>Users</h1>',
            $response->getContent()
        );
    }

    /** 
     * @test 
     * */
    public function it_can_see_users_create_page()
    {
        $response = $this->route('GET', 'entrust-gui::users.create');

        $this->assertResponseOk();
        $this->assertContains(
            '<h1>Create User</h1>',
            $response->getContent()
        );
    }

    /** 
     * @test 
     * */
    public function it_can_see_role_index_page()
    {
        $response = $this->route('GET', 'entrust-gui::roles.index');

        $this->assertResponseOk();
        $this->assertContains(
            '<h1>Roles</h1>',
            $response->getContent()
        );
    }

    /** 
     * @test 
     * */
    public function it_can_see_role_create_page()
    {
        $response = $this->route('GET', 'entrust-gui::roles.create');

        $this->assertResponseOk();
        $this->assertContains(
            '<h1>Create Role</h1>',
            $response->getContent()
        );
    }

    /** 
     * @test 
     * */
    public function it_can_see_permission_index_page()
    {
        $response = $this->route('GET', 'entrust-gui::permissions.index');

        $this->assertResponseOk();
        $this->assertContains(
            '<h1>Permissions</h1>',
            $response->getContent()
        );
    }

    /** 
     * @test 
     * */
    public function it_can_see_permission_create_page()
    {
        $response = $this->route('GET', 'entrust-gui::permissions.create');

        $this->assertResponseOk();
        $this->assertContains(
            '<h1>Create Permission</h1>',
            $response->getContent()
        );
    }

}
