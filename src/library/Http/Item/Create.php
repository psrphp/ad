<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Http\Item;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Database\Db;
use PsrPHP\Form\Builder;
use PsrPHP\Form\Component\Col;
use PsrPHP\Form\Component\Row;
use PsrPHP\Form\Component\SwitchItem;
use PsrPHP\Form\Component\Switchs;
use PsrPHP\Form\Field\Code;
use PsrPHP\Form\Field\Cover;
use PsrPHP\Form\Field\Hidden;
use PsrPHP\Form\Field\Input;
use PsrPHP\Request\Request;
use PsrPHP\Router\Router;

class Create extends Common
{
    public function get(
        Db $db,
        Router $router,
        Request $request,
    ) {
        $billboard = $db->get('psrphp_ad_billboard', '*', [
            'id' => $request->get('billboard_id'),
        ]);

        $form = new Builder('添加广告');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Hidden('billboard_id', $billboard['id'])),
                    (new Switchs('类型', 'type', 'code'))->addSwitch(
                        (new SwitchItem('图片', 'image'))->addItem(
                            (new Cover('图片', 'img', null, $router->build('/psrphp/admin/tool/upload'))),
                            (new Input('链接地址', 'data[url]', null)),
                        ),
                        (new SwitchItem('代码', 'code'))->addItem(
                            (new Code('代码', 'data[code]'))
                        )
                    ),
                    (new Input('备注', 'tips')),
                )
            )
        );
        return $form;
    }

    public function post(
        Db $db,
        Request $request
    ) {
        $billboard = $db->get('psrphp_ad_billboard', '*', [
            'id' => $request->post('billboard_id'),
        ]);

        $db->insert('psrphp_ad_item', [
            'billboard_id' => $billboard['id'],
            'type' => $request->post('type'),
            'data' => json_encode($request->post('data', []), JSON_UNESCAPED_UNICODE),
        ]);

        return Response::success('操作成功！');
    }
}
