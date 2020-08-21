<?php
namespace Star\Laravel\Robot\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class RobotSent
{
    use Queueable, SerializesModels;

    /**
     * The request uri.
     *
     * @var string
     */
    public $uri;

    /**
     * The request params.
     *
     * @var string
     */
    public $options;

    /**
     * The response data.
     *
     * @var array
     */
    public $result;


    /**
     * Create a new event instance.
     *
     * @param $uri
     * @param $options
     * @param $result
     */
    public function __construct($uri, $options, $result)
    {
        $this->uri = $uri;
        $this->options = $options;
        $this->result = $result;
    }
}
