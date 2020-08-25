<?php
namespace Laravel\Robot\Tests;


use Starme\Laravel\Robot\Drivers\DingTalk;
use Starme\Laravel\Robot\RobotManager;
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