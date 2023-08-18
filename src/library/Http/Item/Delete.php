<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Http\Item;

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
        $db->delete('psrphp_ad_item', [
            'id' => $request->get('id'),
        ]);

        return Response::success('操作成功！');
    }
}
