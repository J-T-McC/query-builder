<?php

require_once('../vendor/autoload.php');

use Database\Services\QueryBuilder\Builder;
use Database\Drivers\MySQL;
use Database\Drivers\SQLite;

$config = require_once(__DIR__ . '/config/database.php');

\Database\Drivers\DatabaseDriver::setConfig($config);

$sqlite = new Builder(new SQLite);

$sqlite->raw(file_get_contents(__DIR__ . '/stubs/migrations/sqlite/users.sql'));
$sqlite->raw(file_get_contents(__DIR__ . '/stubs/migrations/sqlite/projects.sql'));
$sqlite->raw(file_get_contents(__DIR__ . '/stubs/seeds/users.sql'));
$sqlite->raw(file_get_contents(__DIR__ . '/stubs/seeds/projects.sql'));

dump($sqlite->table('users')->where('id', '=', 5)->first());

$sqlite->table('users')
    ->where('id', '=', 5)
    ->update([
        'email' => 'updated@example.com'
    ]);

dd($sqlite->table('users')->where('id', '=', 5)->first());

function dd(...$props)
{
    dump(...$props);
    exit();
}

function dump(...$props)
{
    foreach ($props as $prop) {
        print_r("\r\n");
        print_r($prop);
        print_r("\r\n");
    }
}