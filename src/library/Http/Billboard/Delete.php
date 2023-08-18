<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Http\Billboard;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Database\Db;
use PsrPHP\Request\Request;

class Delete extends Common
{
    public function get(
        Db $db,
        Request $request,
    ) {
        $billboard = $db->get('psrphp_ad_billboard', '*', [
            'id' => $request->get('id'),
        ]);
        $db->delete('psrphp_ad_item', [
            'billboard_id' => $billboard['id'],
        ]);
        $db->delete('psrphp_ad_billboard', [
            'id' => $request->get('id'),
        ]);
        return Response::success('操作成功！');
    }
}
