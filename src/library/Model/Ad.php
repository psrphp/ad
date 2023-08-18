<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Model;

use PsrPHP\Database\Db;
use PsrPHP\Framework\Framework;
use PsrPHP\Template\Template;

class Ad
{
    public static function render(string $name): string
    {
        return Framework::execute(function (
            Db $db,
            Template $template
        ) use ($name): string {
            if ($billboard = $db->get('psrphp_ad_billboard', '*', [
                'name' => $name,
            ])) {
                if ($items = $db->rand('psrphp_ad_item', '*', [
                    'billboard_id' => $billboard['id'],
                    'state' => 1,
                    'LIMIT' => 1,
                ])) {
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
                            break;
                    }
                }
            };
            return '';
        });
    }
}
