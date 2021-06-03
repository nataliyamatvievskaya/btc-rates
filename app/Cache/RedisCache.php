<?php


namespace App\Cache;


use Predis\Client;

/**
 * Class RedisCache
 * @package App\Cache
 */
class RedisCache implements ICache
{

    static public $redis;

	/**
	 * init static
	 */
    static public function init(){

        $config = [
            'schema' => 'tcp',
            'host' => 'redis',
            'port' => 6379,
        ];

        self::$redis = new Client($config);

    }

	/**
	 * @param $key
	 * @return string|null
	 */
    static public function get($key): ?string
    {
        $value = self::$redis->get($key);
        if ($value) {
            return $value;
        }
        return null;
    }

	/**
	 * @param $key
	 * @param $value
	 */
    static public function set($key, $value): void
    {
        self::$redis->getset($key, $value);
    }

	/**
	 * @param $key
	 * @param int $ttl
	 */
    static public function expire($key, $ttl = 60): void
    {
        self::expire($key, $ttl);
    }
}