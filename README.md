# PDO plus

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/5216e5b457684f5bb43d727bceb3cc58)](https://www.codacy.com/gh/filisko/pdo-plus/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=filisko/pdo-plus&amp;utm_campaign=Badge_Grade)
[![Coverage Status](https://coveralls.io/repos/github/filisko/pdo-plus/badge.svg?branch=github-actions)](https://coveralls.io/github/filisko/pdo-plus?branch=github-actions)
[![tests](https://github.com/filisko/pdo-plus/actions/workflows/tests.yml/badge.svg)](https://github.com/filisko/pdo-plus/actions/workflows/tests.yml)

PDO plus extends PDO in order to log all your queries. This package also includes a Bar Panel for Tracy (useful for legacy projects), see the result below.

![PDO logger with Tracy](https://i.snag.gy/AbESVC.jpg "PDO logger with Tracy")

## Versions

| Release | Supported PHP versions |
| --- | --- |
| 4.x.x / master (here now) | 8.0 |
| [3.x.x](https://github.com/filisko/pdo-plus/tree/3.x.x) | 7.2, 7.3, 7.4 |


## Installation

Install via composer:

```shell
composer require filisko/pdo-plus
```

## How to use

In this example we are using two different PDO instances just to show that it's doable.

```php
// Create an instance using PDO plus
$pdoConnection1 = new \Filisko\PDOplus\PDO('mysql:host=127.0.0.1;dbname=my_db', 'my_user', 'my_pass');
$pdoConnection2 = new \Filisko\PDOplus\PDO('mysql:host=127.0.0.1;dbname=my_other_db', 'my_user', 'my_pass');

// ... our SQL queries ...

// Dump logged queries of PDO connection 1
var_dump($pdoConnection1->getLog());

// --- the following code shows how to integrate with Tracy debugger

// Instance for Tracy BarPanel for connection 1
$db1Panel = new \Filisko\PDOplus\Tracy\BarPanel($pdoConnection1);
$db1Panel->title = "DB 1 Panel";

// Instance for Tracy BarPanel for connection 2
$db2Panel = new \Filisko\PDOplus\Tracy\BarPanel($pdoConnection2);
$db2Panel->title = "DB 2 Panel";

// Enables Tracy debugger and adds panels for each connection (easy to integrate with legacy apps!)
\Tracy\Debugger::enable();
\Tracy\Debugger::getBar()->addPanel($db1Panel);
\Tracy\Debugger::getBar()->addPanel($db2Panel);
```

## Tests

Run tests:

```shell
composer run-script test
```
