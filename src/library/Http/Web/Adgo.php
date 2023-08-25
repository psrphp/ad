<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Http\Web;

use App\Psrphp\Admin\Lib\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PsrPHP\Database\Db;
use PsrPHP\Framework\Framework;
use PsrPHP\Request\Request;

class Adgo implements RequestHandlerInterface
{
    public function handle(
        ServerRequestInterface $request
    ): ResponseInterface {
        $method = strtolower($request->getMethod());
        if (in_array($method, ['get', 'put', 'post', 'delete', 'head', 'patch', 'options']) && is_callable([$this, $method])) {
            $resp = Framework::execute([$this, $method]);
            if (is_scalar($resp) || (is_object($resp) && method_exists($resp, '__toString'))) {
                return Response::html((string)$resp);
            }
            return $resp;
        } else {
            return Framework::execute(function (
                ResponseFactoryInterface $responseFactory
            ): ResponseInterface {
                return $responseFactory->createResponse(405);
            });
        }
    }

    public function get(
        Db $db,
        Request $request,
    ) {
        if (!$item = $db->get('psrphp_ad_item', '*', [
            'id' => $request->get('id'),
        ])) {
            return Response::error('页面不存在');
        }

        $data = json_decode($item['data'], true);
        if (isset($data['url']) && is_string($data['url']) && strlen($data['url'])) {
            $db->update('psrphp_ad_item', [
                'click[+]' => 1,
            ], [
                'id' => $item['id']
            ]);

            $db->insert('psrphp_ad_click', [
                'item_id' => $item['id'],
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'referer' => $_SERVER['HTTP_REFERER'] ?? '',
                'url' => $data['url'],
                'time' => date('Y-m-d H:i:s'),
            ]);
            return Response::redirect($data['url']);
        } else {
            return Response::error('页面不存在');
        }
    }
}
