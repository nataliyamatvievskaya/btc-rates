<?php


namespace App\Cache;

/**
 * Interface ICache
 * @package App\Cache
 */
interface ICache
{
	/**
	 * get key
	 * @param $key
	 * @return string|null
	 */
    static public function get($key): ?string;

	/**
	 * set key
	 * @param $key
	 * @param $value
	 */
    static public function set($key, $value):void;

	/**
	 * set expired value
	 * @param $key
	 */
    static public function expire($key):void;

}