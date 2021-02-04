<?php

namespace Database\Drivers;

class MySQL extends DatabaseDriver implements DBDriverInterface
{
    public static function boot()
    {
        static::$dsn = sprintf(
            "mysql:host=%s;dbname=%s;charset=utf8mb4;port=%s",
            static::$config['mysql']['host'],
            static::$config['mysql']['database'],
            static::$config['mysql']['port']
        );

        static::$options = [
            static::$config['mysql']['username'],
            static::$config['mysql']['password'],
        ];
    }
}