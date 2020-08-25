<?php
namespace Starme\Laravel\Robot;


use Illuminate\Support\Manager;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;

class RobotManager extends Manager
{

    /**
     * Get a robot channel instance.
     *
     * @param string $name
     * @return mixed
     */
    public function channel(string $name)
    {
        return $this->driver($name);
    }

    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function driver($driver = null)
    {
        $driver = $driver ?: $this->getDefaultDriver();

        if (is_null($driver)) {
            throw new InvalidArgumentException(sprintf(
                'Unable to resolve NULL driver for [%s].', static::class
            ));
        }

        // If the given driver has not been created before, we will create the instances
        // here and cache it so we can return it next time very quickly. If there is
        // already a driver created by this name, we'll just return that instance.
        if (! isset($this->drivers[$driver])) {
            $this->drivers[$driver] = $this->make($this->resolve($driver));
        }

        return $this->drivers[$driver];
    }

    protected function make($driver)
    {
        return $this->container->make(ChatRobot::class)->setDriver($driver);
    }

    /**
     * Resolve the given log instance by name.
     *
     * @param  string  $name
     * @return \Psr\Log\LoggerInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->configurationFor($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Robot [{$name}] is not defined.");
        }

        if (isset($this->customCreators[$config['driver']])) {
            return $this->callCustomCreator($config);
        }

        $driverMethod = 'create'.ucfirst($config['driver']).'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        }

        throw new InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
    }

    /**
     * Call a custom driver creator.
     *
     * @param array $config
     * @return mixed
     */
    protected function callCustomCreator($config)
    {
        return $this->customCreators[$config['driver']]($this->container, $config);
    }

    /**
     * Create chat driver for config.
     *
     * @param $config
     * @return Drivers\DingTalk
     */
    protected function createDingTalkDriver($config)
    {
        return new Drivers\DingTalk($config);
    }

    /**
     * Get the log connection configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function configurationFor($name)
    {
        return $this->container['config']["robots.connections.{$name}"];
    }

    /**
     * Get the default robot driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->container['config']['robots.default'];
    }

}
