<?php

namespace Starme\Laravel\Robot;


use BadMethodCallException;
use GuzzleHttp\Client;
use Illuminate\Events\Dispatcher;
use Illuminate\Contracts\Pipeline\Pipeline;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;

class ChatRobot
{

    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * @var array
     */
    protected $config;

    protected $middleware = [
        Middleware\RateLimited::class,
    ];
    /**
     * @var Pipeline
     */
    protected $pipe;

    public function __construct(Dispatcher $events, Pipeline $pipe, $config)
    {
        $this->events = $events;
        $this->pipe = $pipe;
        $this->config = $config;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    public function send($type, $data)
    {
        $data = $this->$type($data);

        if ( ! $data) {
            throw new InvalidArgumentException(sprintf(
                'Missing parameter data for [%s].', static::class
            ));
        }
        return $this->request($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function markdown(array $data) : array
    {
        if( ! isset($data['content'])) {
            return [];
        }

        return [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $data['title'] ?? '',
                'text' => $data['content'],
            ],
            'at' => $data['at'] ?? []
        ];
    }

    public function text(array $data) : array
    {
        if( ! isset($data['content'])) {
            return [];
        }

        return [
            'msgtype' => 'text',
            'text' => [
                'content' => $data['content'],
            ],
            'at' => $data['at'] ?? []
        ];
    }

    /**
     * @param array $data
     * @return mixed
     */
    protected function request($data)
    {
        return $this->middleware()->then(function() use ($data) {
            $this->client = $this->client ?? $this->client();
            $url = $this->driver->getUrl();
            if ( ! $this->shouldSendRequest($url, $data)) {
                return false;
            }
            $response = $this->client->request('POST', $url, ['json' => $data]);

            $result = json_decode($response->getBody()->getContents(), true);

            $this->events->dispatch(
                new Events\RobotSent($url, $data, $result)
            );
            return $result;
        });
    }

    /**
     * Determines if the request can be sent.
     *
     * @param string $uri
     * @param array $options
     * @return bool
     */
    protected function shouldSendRequest($uri, $options)
    {
        return $this->events->until(
                new Events\RobotSending($uri, $options)
            ) !== false;
    }

    /**
     * Set http request client.
     * @return Client
     */
    protected function client()
    {
        return new Client([
            'headers'  => [
                'Content-Type' => 'application/json',
                'Charset'      => 'utf-8',
            ],
            'base_uri' => $this->driver->getBaseUri(),
            'verify'   => false,
        ]);
    }

    public function middleware()
    {
        return $this->pipe->through($this->middleware);
    }

    public function __call($method, $arguments)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $arguments);
        }

        if(method_exists($this->driver, $method)) {
            return $this->request($this->driver->$method($arguments));
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', class_basename($this->driver), $method
        ));
    }
}
