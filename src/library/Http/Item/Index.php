<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Http\Item;

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
        $billboard = $db->get('psrphp_ad_billboard', '*', [
            'id' => $request->get('billboard_id'),
        ]);
        $items = $db->select('psrphp_ad_item', '*', [
            'billboard_id' => $billboard['id']
        ]);
        return $template->renderFromFile('item/index@psrphp/ad', [
            'billboard' => $billboard,
            'items' => $items,
        ]);
    }
}
