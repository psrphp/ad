<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Model;

use Psr\Log\LoggerInterface;
use PsrPHP\Database\Db;
use PsrPHP\Framework\Framework;
use PsrPHP\Template\Template;
use Throwable;

class Ad
{
    public static function render(string $name): string
    {
        return Framework::execute(function (
            Db $db,
            Template $template,
            LoggerInterface $logger,
        ) use ($name): string {
            if ($billboard = $db->get('psrphp_ad_billboard', '*', [
                'name' => $name,
            ])) {
                if ($items = $db->rand('psrphp_ad_item', '*', [
                    'billboard_id' => $billboard['id'],
                    'state' => 1,
                    'LIMIT' => 1,
                ])) {
                    try {
                        $item = $items[0];
                        $data = json_decode($item['data'], true);
                        switch ($item['type']) {
                            case 'image':
                                return '<a href="' . ($data['url'] ?? '') . '" target="_blank"><img src="' . ($data['img'] ?? '') . '"></a>';
                                break;

                            case 'code':
                                return $data['code'] ?? '';
                                break;

                            case 'tpl':
                                return $template->renderFromString($data['tpl'] ?? '');
                                break;

                            default:
                                $logger->error('广告渲染错误：[name:' . $name . ' id:' . $item['id'] . '] 类型' . $item['type'] . '不支持~');
                                return '<div style="border: 1px solid red;padding: 10px;color: red;">广告 <code>' . $name . '</code> 渲染错误，请看系统日志~</div>';
                                break;
                        }
                    } catch (Throwable $th) {
                        $logger->error('广告渲染错误：[name:' . $name . ' id:' . $item['id'] . ']' . $th->getMessage(), $th->getTrace());
                        return '<div style="border: 1px solid red;padding: 10px;color: red;">广告 <code>' . $name . '</code> 渲染错误，请看系统日志~</div>';
                    }
                }
            } else {
                return '<div style="border: 1px solid red;padding: 10px;color: red;">广告 <code>name:' . $name . '</code> 不存在，请在后台创建~</div>';
            };
        });
    }
}
