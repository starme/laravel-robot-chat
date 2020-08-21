<?php
namespace Starme\Laravel\Robot\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class RobotSending
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
     * Create a new event instance.
     *
     * @param $uri
     * @param $options
     */
    public function __construct($uri, $options)
    {
        $this->uri = $uri;
        $this->options = $options;
    }
}
