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
 * Hprose/BaseService.php                                 *
 *                                                        *
 * hprose base service class for php 5.3+                 *
 *                                                        *
 * LastModified: May 8, 2015                              *
 * Author: Ma Bingyao <andot@hprose.com>                  *
 *                                                        *
\**********************************************************/

namespace Hprose\Workerman\Base {

    if(!class_exists("\Workerman\Http\Service\WorkermanHttpService")) {
        require_once __DIR__."/WorkermanHttpService.php";
    }


    use Hprose\Http\Service as HttpService;
    use Workerman\Http\Service\WorkermanHttpService;

    class Service extends \Hprose\Service {
        public $user_fatal_error_handler = null;
        public function __construct() {
            parent::__construct();
        }

        public function httpHandle($request, $response) {
            $hprose_http_service = new WorkermanHttpService();
            return $hprose_http_service->handle($request, $response);
        }

        public function socketHandle($request, $response) {
            ob_start();
            ob_implicit_flush(0);
            $hprose_http_service = new HttpService();
            return $hprose_http_service->handle($request, $response);
        }

        public function websocketHandle($request, $response) {
            ob_start();
            ob_implicit_flush(0);
            $hprose_http_service = new HttpService();
            return $hprose_http_service->handle($request, $response);
        }
    }
}
