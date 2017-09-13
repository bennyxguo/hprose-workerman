<?php
/**********************************************************\
|                                                          |
|                          hprose                          |
|                                                          |
| Official WebSite: http://www.hprose.com/                 |
|                   http://www.hprose.org/                 |
|                                                          |
\**********************************************************/

/**********************************************************\
 *                                                        *
 * HproseWorkermanService.php                             *
 *                                                        *
 * hprose service class for php 5.3+                      *
 * This client version supports the Workerman functions.  *
 *                                                        *
 * LastModified: Oct 28, 2015                             *
 * Author: Kevin Ingwersen <ingwie2000@gmail.com>         *
 *         http://ingwie.me                               *
 *                                                        *
\**********************************************************/

/**
 * @file
 * This file contains functionality to hook into the Workerman system.
 */

/**
 * This is the actual class that provides the bindings.
 * It overrides the onMessage callback to handle it with hprose.
 * It provides a method to access a reference of the original hprose
 * instance and also a shorthand method to add functions/class methods.
 * It is recommended to use the actual hprose api.
 *
 * An example of how it is used:
 *
 * ```php
 * <?php
 * include "hprose-php/Hprose.php";
 * include "Workerman/Autoloader.php";
 *
 * function hello($w) { return "Hello, $w!"; }
 *
 * $client = new \Workerman\Hprose("127.0.0.1", 9999);
 * $client->count = 4; # Make 4 workers.
 * $hprose = $client->hprose();
 * $hprose->addFunction("hello");
 *
 * Worker::runAll();
 * ?>
 * ```
 *
 * From now on, there is a server on localhost:9999, ready to take hprose commands!
 */
namespace Workerman {

    if(!class_exists("\Hprose\Workerman\Services\Http")) {
        require_once __DIR__."/Services/Http.php";
    }

    use \Workerman\Worker;
    use Exception;

    class Hprose extends Worker {
        // Initialize
        private $_hprose;
        public function __construct($uri, $opts = array()) {
            parent::__construct($uri, $opts);
            $this->name = "hprose";
            $p = parse_url($uri);
            if ($p) {
                switch (strtolower($p['scheme'])) {
                    case 'ws':
                    case 'wss':
                        throw new Exception("Can't support this scheme: {$p['scheme']}");
                        break;
                    case 'http':
                    case 'https':
                        $this->_hprose = new \Hprose\Workerman\Services\Http($this);
                        break;
                    case 'tcp':
                    case 'tcp4':
                    case 'tcp6':
                    case 'ssl':
                    case 'sslv2':
                    case 'sslv3':
                    case 'tls':
                    case 'unix':
                        throw new Exception("Can't support this scheme: {$p['scheme']}");
//                        $this->_hprose = new HproseWorkermanService($this, 'socket');
                        break;
                    default:
                        throw new Exception("Can't support this scheme: {$p['scheme']}");
                }
            }
            else {
                throw new \Exception("Can't parse this url: " . $uri);
            }
        }

        public function &hprose() { return $this->_hprose; }

        // Setup the methods
        public function run() {
            $this->onMessage = array($this, 'onMessage');
            parent::run();
        }

        // The handler
        public function onMessage($conn, $data) {
            $this->_hprose->handle($conn, $data);
        }

    }

}
