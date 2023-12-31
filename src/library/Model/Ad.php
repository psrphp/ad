<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Model;

use Medoo\Medoo;
use Psr\Log\LoggerInterface;
use PsrPHP\Database\Db;
use PsrPHP\Framework\Framework;
use PsrPHP\Router\Router;
use PsrPHP\Template\Template;
use Throwable;

class Ad
{
    private static $errhtml = '<div style="border: 1px solid red;padding: 10px;color: red;">广告渲染错误，请看系统日志~</div>';

    public static function render(string $name): string
    {
        return Framework::execute(function (
            Db $db,
            Router $router,
        ) use ($name): string {
            if (!$billboard = $db->get('psrphp_ad_billboard', '*', [
                'name' => $name,
            ])) {
                return '<div style="border: 1px solid red;padding: 10px;color: red;">广告 <code>name:' . $name . '</code> 不存在，请在后台创建~</div>';
            }

            $where = [
                'billboard_id' => $billboard['id'],
                'state' => 1,
                'max_showtimes[>]' => Medoo::raw('showtimes'),
                'starttime[<]' => date('Y-m-d H:i:s'),
                'endtime[>]' => date('Y-m-d H:i:s'),
                'LIMIT' => 1,
            ];
            $agent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
            if (strpos($agent, 'iphone') || strpos($agent, 'android')) {
                $where['mobile'] = 1;
            } else {
                $where['pc'] = 1;
            }
            if (!$items = $db->rand('psrphp_ad_item', '*', $where)) {
                return '';
            }

            $date = date('Y-m-d');
            $hour = date('H');
            $item = $items[0];
            $res = self::renderItem($item);
            if ($res != self::$errhtml) {
                $db->update('psrphp_ad_item', [
                    'showtimes[+]' => 1,
                ], [
                    'id' => $item['id'],
                ]);

                if ($db->get('psrphp_ad_show', '*', [
                    'item_id' => $item['id'],
                    'date' => $date,
                    'hour' => $hour,
                ])) {
                    $db->update('psrphp_ad_show', [
                        'times[+]' => 1,
                    ], [
                        'item_id' => $item['id'],
                        'date' => $date,
                        'hour' => $hour,
                    ]);
                } else {
                    $db->insert('psrphp_ad_show', [
                        'item_id' => $item['id'],
                        'date' => $date,
                        'hour' => $hour,
                        'times' => 1,
                    ]);
                }
            }
            return '<div onclick="var xmlhttp = new XMLHttpRequest();xmlhttp.open(\'post\', \'' . $router->build('/psrphp/ad/web/stat', ['id' => $item['id']]) . '\', true);xmlhttp.send();">' . $res . '</div>';
        });
    }

    public static function renderItem(array $item, array $billboard = null): string
    {
        return Framework::execute(function (
            Db $db,
            Router $router,
            Template $template,
            LoggerInterface $logger,
        ) use ($item, $billboard): string {
            try {
                $data = json_decode($item['data'], true);
                switch ($item['type']) {
                    case 'image':
                        if (isset($data['url']) && !is_null($data['url']) && strlen($data['url'])) {
                            return '<a href="' . $router->build('/psrphp/ad/web/jump', ['id' => $item['id']]) . '" target="_blank"><img src="' . ($data['img'] ?? '') . '" style="max-width: 100%;"></a>';
                        } else {
                            return '<img src="' . ($data['img'] ?? '') . '" style="max-width: 100%;">';
                        }
                        break;

                    case 'WYSIWYG':
                        return $data['content'] ?? '';
                        break;

                    case 'html':
                        return $data['html'] ?? '';
                        break;

                    case 'tpl':
                        return $template->renderFromString($data['tpl'] ?? '', [
                            'billboard' => $billboard ?: $db->get('psrphp_ad_billboard', '*', [
                                'id' => $item['billboard_id'],
                            ]),
                            'item' => $item,
                        ]);
                        break;

                    default:
                        $logger->error('广告渲染错误：[billboard_id:' . $item['billboard_id'] . ' id:' . $item['id'] . '] 类型' . $item['type'] . '不支持~');
                        return self::$errhtml;
                        break;
                }
            } catch (Throwable $th) {
                $logger->error('广告渲染错误：[billboard_id:' . $item['billboard_id'] . ' id:' . $item['id'] . ']' . $th->getMessage(), $th->getTrace());
                return self::$errhtml;
            }
        });
    }
}
