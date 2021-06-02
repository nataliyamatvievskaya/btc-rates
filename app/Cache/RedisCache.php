<?php


namespace App\Cache;


use Predis\Client;

class RedisCache implements ICache
{

    static public $redis;
    static public function init(){

        $config = [
            'schema' => 'tcp',
            'host' => 'redis',
            'port' => 6379,
        ];

        self::$redis = new Client($config);

    }

    static public function get($key): ?string
    {
        $value = self::$redis->get($key);
        if ($value) {
            return $value;
        }
        return null;
    }

    static public function set($key, $value): void
    {
        self::$redis->getset($key, $value);
    }

    static public function inCache($key): void
    {
        // TODO: Implement inCache() method.
    }
}