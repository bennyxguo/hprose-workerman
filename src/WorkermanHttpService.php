<?php
/**
 * Created by WeyeeDev.
 * Creator: guojing
 * Date: 2017/9/13 0013
 * Time: 11:15
 */
namespace Workerman\Http\Service;

use Hprose\Http\Service as HproseHttpService;
use Hprose\Future;

class WorkermanHttpService extends HproseHttpService
{
    public function writeResponse($data, $context) {
        echo $data;
    }

    public function handle($request = null, $response = null) {
        $context = $this->createContext($request, $response);
        $self = $this;
        $this->userFatalErrorHandler = function($error) use ($self, $context) {
            $self->writeResponse($self->endError($error, $context), $context);
        };

//            $this->sendHeader($context);

        $result = '';
        if ($this->isGet($context)) {
            if ($this->get) {
                $result = $this->doFunctionList();
            }
        }
        elseif ($this->isPost($context)) {
            $result = $this->defaultHandle($this->readRequest($context), $context);
        }
        else {
            $result = $this->doFunctionList();
        }
        if (Future\isFuture($result)) {
            $result->then(function($result) use ($self, $context) {
//                    $self->header('Content-Length', strlen($result), $context);
                $self->writeResponse($result, $context);
            });
        }
        else {
//                $this->header('Content-Length', strlen($result), $context);
            $this->writeResponse($result, $context);
        }
        return $context->response;
    }
}
