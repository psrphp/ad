<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Http\Billboard;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Database\Db;
use PsrPHP\Form\Builder;
use PsrPHP\Form\Col;
use PsrPHP\Form\Row;
use PsrPHP\Form\Input;
use PsrPHP\Form\Hidden;
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
                    (new Input('标题', 'title', $billboard['title']))->setRequired(),
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
            'title' => $request->post('title'),
        ], [
            'id' => $billboard['id'],
        ]);
        return Response::success('操作成功！');
    }
}
