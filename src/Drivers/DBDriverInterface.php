<?php

namespace Database\Drivers;

interface DBDriverInterface
{
    /**
     * Register the PDO dsn and options for a database driver
     */
    static function boot();
}