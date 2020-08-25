<?php
namespace Starme\Laravel\Robot\Drivers;

use Starme\Laravel\Robot\DriverInterface;

class DingTalk implements DriverInterface
{

    protected $config;

    /**
     * Create new Robot Client.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getUrl()
    {
        return '/robot/send?access_token=' . $this->config['token'];
    }

    public function getBaseUri()
    {
        return $this->config['base_uri'];
    }
}
