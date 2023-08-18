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

class Update extends Common
{
    public function get(
        Db $db,
        Router $router,
        Request $request,
    ) {
        $item = $db->get('psrphp_ad_item', '*', [
            'id' => $request->get('id'),
        ]);
        $data = json_decode($item['extra'], true);
        $form = new Builder('编辑广告');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-8'))->addItem(
                    (new Hidden('id', $item['id'])),
                    (new Switchs('类型', 'type', $item['type']))->addSwitch(
                        (new SwitchItem('图片', 'image'))->addItem(
                            (new Cover('图片', 'data[image]', $data['image'] ?? '', $router->build('/psrphp/admin/tool/upload'))),
                            (new Input('链接地址', 'data[url]', $data['url'] ?? ''))
                        ),
                        (new SwitchItem('代码', 'code'))->addItem(
                            (new Code('代码', 'data[code]', $data['code'] ?? ''))
                        )
                    ),
                    (new Input('备注', 'tips', $item['tips'])),
                )
            )
        );
        return $form;
    }

    public function post(
        Db $db,
        Request $request,
    ) {
        $item = $db->get('psrphp_ad_item', '*', [
            'id' => $request->post('id'),
        ]);
        $db->update('psrphp_ad_item', [
            'type' => $request->post('type'),
            'data' => json_encode($request->post('data', []), JSON_UNESCAPED_UNICODE),
            'tips' => $request->post('tips'),
        ], [
            'id' => $item['id'],
        ]);
        return Response::success('操作成功！');
    }
}
