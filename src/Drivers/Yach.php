<?php
namespace Starme\Robot\Drivers;

use Starme\Robot\DriverInterface;

class Yach implements DriverInterface
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

    /**
     * @param array $data
     * @return array
     */
    public function toMarkdown(array $data)
    {
        return [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $data['title'],
                'text' => $data['content'],
            ],
            'at' => [
                'atYachIds' => $data['at'] ?? ''
            ]
        ];
    }

    public function toText(array $data)
    {
        return [
            'msgtype' => 'text',
            'text' => [
                'content' => $data['content'],
            ],
            'at' => [
                'atYachIds' => $data['at'] ?? ''
            ]
        ];
    }

    public function getUrl()
    {
        $t = floor(microtime(true) * 1000);
        $uri = '/robot/send?access_token=%s&timestamp=%s&sign=%s';
        $sign = urlencode(base64_encode(hash_hmac(
            'SHA256',
            $t . "\n" . $this->config['secret'],
            $this->config['secret'],
            true
        )));
        return sprintf($uri, $this->config['token'], $t, $sign);
    }

    public function getBaseUri()
    {
        return $this->config['base_uri'];
    }

}

