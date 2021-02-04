<?php


namespace Database\Drivers;

use PDO;

class DatabaseDriver
{
    static array $connections = [];

    protected static string $dsn;
    protected static array $config = [];
    protected static array $options = [];

    /**
     * @return \PDO
     */
    public static function getConnection()
    {
        static::boot();

        if (! isset(static::$connections[static::class])) {
            $connection = new PDO(static::$dsn, ...static::$options);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $connection->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
            static::$connections[static::class] = $connection;
        }

        return static::$connections[static::class];
    }

    /**
     * @param array $config
     */
    public static function setConfig(array $config) {
        static::$config = $config;
    }
}