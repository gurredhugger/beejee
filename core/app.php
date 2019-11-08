<?php


namespace app\core;

class app
{
    public static $config;
    public function run(array $config)
    {
        static::$config = $config;
    }
}
