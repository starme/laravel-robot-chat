<?php
namespace Starme\Robot\Tests;


use Starme\Robot\Drivers\DingTalk;
use Starme\Robot\RobotManager;
use Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function testProvider()
    {
        $this->assertInstanceOf(RobotManager::class, app('robot'));
    }

    public function testRobot()
    {
        $this->assertInstanceOf(DingTalk::class, app('robot')->channel('police')->getDriver());
    }
}