<?php
namespace Starme\Laravel\Robot\Middleware;

class RateLimited
{

    protected $allow;
    protected $every;
    /**
     * @var \Illuminate\Config\Repository
     */
    protected $rate_key;

    public function __construct()
    {
        $this->rate_key = config('robots.rate_cache_key');
        list($this->allow, $this->every) = config('robots.rate_allow');
    }

    /**
     * 处理队列任务
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, $next)
    {
        $result = null;
        app('redis')->throttle($this->rate_key)
            ->allow($this->allow)->every($this->every)
            ->then(function () use ($job, $next, &$result) {

                $result = $next($job);
                return $result;

            }, function ($e) use ($job) {
                //超时
            });
        return $result;
    }
}
