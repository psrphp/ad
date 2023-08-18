<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Model;

use PsrPHP\Database\Db;
use PsrPHP\Framework\Framework;

class Ad
{
    public static function render(string $name): string
    {
        return Framework::execute(function (
            Db $db
        ) use ($name): string {
            if ($billboard = $db->get('psrphp_ad_billboard', '*', [
                'name' => $name,
            ])) {
                $item = $db->rand('psrphp_ad_item', '*', [
                    'billboard_id' => $billboard['id'],
                    'state' => 1,
                ]);
                $data = json_decode($item['data'], true);
                switch ($item['type']) {
                    case 'image':
                        return '<a href="' . $data['url'] . '" target="_blank"><img src="' . $data['image'] . '"></a>';
                        break;

                    case 'code':
                        return $data['code'];
                        break;

                    default:
                        break;
                }
            };
            return '';
        });
    }
}
