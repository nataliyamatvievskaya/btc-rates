<?php


namespace App\Cache;


interface ICache
{
    static public function get($key): ?string;

    static public function set($key, $value):void;

    static public function inCache($key):void;

}