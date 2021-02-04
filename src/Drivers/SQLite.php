<?php


namespace Database\Drivers;

class SQLite extends DatabaseDriver implements DBDriverInterface
{
    public static function boot()
    {
        static::$dsn = "sqlite:" . static::$config['sqlite']['database'];
    }
}