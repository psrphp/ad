<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Http\Click;

use App\Psrphp\Admin\Http\Common;
use PsrPHP\Database\Db;
use PsrPHP\Request\Request;
use PsrPHP\Template\Template;

class Index extends Common
{
    public function get(
        Db $db,
        Request $request,
        Template $template
    ) {
        $where = [];
        if ($item_id = $request->get('item_id')) {
            $where['item_id'] = $item_id;
        }

        $total = $db->count('psrphp_ad_click', $where);

        $page = $request->get('page', 1);
        $size = 50;
        $where['LIMIT'] = [($page - 1) * $size, $size];
        $where['ORDER'] = [
            'id' => 'DESC'
        ];

        $clicks = $db->select('psrphp_ad_click', '*', $where);

        foreach ($clicks as &$vo) {
            $vo['item'] = $db->get('psrphp_ad_item', '*', [
                'id' => $vo['item_id'],
            ]);
        }

        return $template->renderFromFile('click/index@psrphp/ad', [
            'clicks' => $clicks,
            'maxpage' => ceil($total / $size) ?: 1
        ]);
    }
}
