# PDO plus

PDO plus extends PDO in order to log all your queries (very useful for legacy apps!). It also includes a Bar Panel for Tracy, see the result below.

## Result
![PDO logger with Tracy](https://i.snag.gy/AbESVC.jpg "PDO logger with Tracy")

## Installation
Install it via composer:

```shell
composer require filisko/pdo-plus
```

## How to use

In this example we are using two different PDO instances just to show that it's doable.

```php
// Create an instance using PDO plus
$pdoConnection1 = new \Filisko\PDOplus\PDO('mysql:host=127.0.0.1;dbname=my_db', 'my_user', 'my_pass');
$pdoConnection2 = new \Filisko\PDOplus\PDO('mysql:host=127.0.0.1;dbname=my_other_db', 'my_user', 'my_pass');

// ... our PDO queries ...

// Dump all our executed queries of PDO connection 1
var_dump($pdoConnection1->getLog());

// Instance for Tracy BarPanel for connection 1
$db1Panel = new \Filisko\PDOplus\Tracy\BarPanel($pdoConnection1);
$db1Panel->title = "DB 1 Panel";

// Instance for Tracy BarPanel for connection 2
$db2Panel = new \Filisko\PDOplus\Tracy\BarPanel($pdoConnection2);
$db2Panel->title = "DB 2 Panel";

// Add panels to Tracy Bar
\Tracy\Debugger::getBar()->addPanel($db1Panel);
\Tracy\Debugger::getBar()->addPanel($db2Panel);
```
