# PDO plus

PDO plus is a wrapper for PDO which will allow you to see all your executed queries, it will also try to simulate bind queries. It also includes a Bar Panel for Tracy, see the result below.

## Result
![PDO logger with Tracy](https://i.snag.gy/AbESVC.jpg "PDO logger with Tracy")

## Installation
Install it via composer:

`composer require filisko/pdo-plus`

## How to use

```php
// Create an instance using PDO plus
$pdo = new \Filisko\PDOplus\PDO('mysql:host=127.0.0.1;dbname=my_db', 'my_user', 'my_pass');

// ... our PDO queries ...


// Dump all our executed queries
var_dump($pdo->getLog());


// Create an instance for Tracy Bar Panel and pass the PDO instance
$panel = new \Filisko\PDOplus\Tracy\BarPanel($pdo);

// We add the panel to Tracy
\Tracy\Debugger::getBar()->addPanel($panel);
```
