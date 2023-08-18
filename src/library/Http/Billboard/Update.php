<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Http\Billboard;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Database\Db;
use PsrPHP\Form\Builder;
use PsrPHP\Form\Component\Col;
use PsrPHP\Form\Component\Row;
use PsrPHP\Form\Field\Hidden;
use PsrPHP\Form\Field\Input;
use PsrPHP\Request\Request;

class Update extends Common
{
    public function get(
        Db $db,
        Request $request,
    ) {
        $billboard = $db->get('psrphp_ad_billboard', '*', [
            'id' => $request->get('id'),
        ]);
        $form = new Builder('编辑广告位');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-8'))->addItem(
                    (new Hidden('id', $billboard['id'])),
                    (new Input('备注', 'tips', $billboard['tips'])),
                )
            )
        );
        return $form;
    }

    public function post(
        Db $db,
        Request $request,
    ) {
        $billboard = $db->get('psrphp_ad_billboard', '*', [
            'id' => $request->post('id'),
        ]);
        $db->update('psrphp_ad_billboard', [
            'tips' => $request->post('tips'),
        ], [
            'id' => $billboard['id'],
        ]);
        return Response::success('操作成功！');
    }
}
