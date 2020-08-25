<?php
namespace Starme\Robot;

interface DriverInterface
{
    /**
     * DriverInterface constructor.
     * @param $config
     */
    public function __construct($config);

    /**
     * @return mixed
     */
    public function getUrl();

    /**
     * @return mixed
     */
    public function getBaseUri();
}