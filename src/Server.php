<?php

/** ---------------------------------------------------------
|
| Workerman Server with Hprose integration
| Workerman 服务端 - 集成了Hprose
|
| ---------------------------------------------------------
| Developer: TriDiamond <code.tridiamond@gmail.com>
| LastModified: 2017/09/14
 * ---------------------------------------------------------
 */

namespace Hprose\Workerman;

use Workerman\Worker;
use Exception;

class Server extends Worker {
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
        $this->_hprose->httpHandle($conn, $data);
    }

}