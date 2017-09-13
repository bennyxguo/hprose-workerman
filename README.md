# Hprose and Workerman - MERGED!

Workerman is a framework for building multi-process servers in PHP. It allows you to build a Webserver or another kind of server - maybe for an API. Hprose is both, a protocol and RPC engine. It is modern, lightweight and amazingly fast. It's own serialization format supports objects, binary data and even more!

## Introduction to Hprose

Hprose is a High Performance Remote Object Service Engine.

It is a modern, lightweight, cross-language, cross-platform, object-oriented, high performance, remote dynamic communication middleware. It is not only easy to use, but powerful. You just need a little time to learn, then you can use it to easily construct cross language cross platform distributed application system.

Hprose supports many programming languages, for example:

- AAuto Quicker
- ActionScript
- ASP
- C++
- Dart
- Delphi/Free Pascal
- dotNET(C#, Visual Basic...)
- Golang
- Java
- JavaScript
- Node.js
- Objective-C
- Perl
- PHP
- Python
- Ruby
- ...

Through Hprose, You can conveniently and efficiently intercommunicate between those programming languages.

## Usage
Install with composer
```
composer require tridiamond/hprose-workerman
```

### Example: Create a Hprose-based workerman setup
```php
<?php
# Standalone
require_once "Workerman/Autoloader.php";
require_once "Hprose.php"; # If you run the native PECL extension, you won't need this.

# Using composer
require_once "vendor/autoload.php";

// Create the worker
$host = "127.0.0.1";
$port = 9999;
$worker = new \Workerman\Hprose($host, $port);
// Set options
$worker->count = 4;
$worker->reloadable = true;

// Add a function
function hello($w) { return "Hello, $w!"; }
$hprose = $worker->hprose();
$hprose->addFunction("hello");

// Start the Workerman framework, run the worker(s)...
\Workerman\Worker::runAll();
```

You now have a Workerman instance with 4 workers listening on your local port `9999`. This server balances requests upon processes and lets you scale your application.

#### Note
This little module was originally coded by [Kevin Ingwersen (Ingwie Phoenix)](https://github.com/IngwiePhoenix), I have completely rebuilded it to make it more efficient and best of all **SUPPORTS latest Hprose and Workerman versions**.

## License
This code is released by the standard MIT license.

## Author
This little module was coded/modified by [TriDiamond (Ingwie Phoenix)](https://github.com/TriDiamond)
Hprose was originally developed by [Ma Bingyao](https://github.com/andot)
