# Query Builder

### NOTE: This repository is not for use in production applications

In my years as a developer I have found myself interacting with different databases using many methods. In the earlier years
I would often be directly using PDO and mysqli, but the later years primarily using query builders like the
Illuminate builder in Laravel. Throughout all of this time I had never actually built a query builder/pdo wrapper myself. 

I wanted to give it a try and see what I could come up with in a couple of days, hopefully encountering and solving the same problems that the developers
who built and maintained the popular packages we all use have. This repo is the result of that personal challenge. 
Although it is ultimately missing some features, I feel like this project has accomplished its goal.

Reading through the tests is a good place to start to see feature implementations followed by the Builder service class.

It currently supports MySQL and SQLite. There is a scratch file located in the ./tests directory that can be used for manual testing.

### Example Usage

```php
use Database\Services\QueryBuilder\Builder;

$config = require_once('/path/to/some/config.php');

\Database\Drivers\DatabaseDriver::setConfig($config);

$mysql = new Builder(new \Database\Drivers\MySQL);

$mysql
    ->table('users')
    ->innerJoin('projects', 'id', 'user_id')
    ->select('users.*', 'projects.project_name')
    ->whereIn('users.id', [1, 2, 3])   
    ->orderBy('users.id', 'DESC')
    ->each(function($row) {
        // ...
    });

$sqlite = new Builder(new \Database\Drivers\SQLite);

$someCount = $sqlite
    ->table('some_table')
    ->where('some_column', '=', 'some_value')
    ->count();
``` 

### Example config
```php
return [
    'mysql' => [
        'host' => 'mysql',
        'port' => 3306,
        'username' => '...',
        'password' => '...',
        'database' => 'my_database',
    ],

    'sqlite' => [
        'database' => ':memory',
    ]
];

```