<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Http\Item;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Database\Db;
use PsrPHP\Form\Builder;
use PsrPHP\Form\Component\Col;
use PsrPHP\Form\Component\Row;
use PsrPHP\Form\Field\Code;
use PsrPHP\Form\Field\Cover;
use PsrPHP\Form\Field\Hidden;
use PsrPHP\Form\Field\Input;
use PsrPHP\Form\Field\Radio;
use PsrPHP\Form\Field\Summernote;
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
        $form = new Builder('编辑广告');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-8'))->addItem(
                    (new Hidden('id', $item['id'])),
                    ...(function () use ($router, $item): array {
                        $data = json_decode($item['data'], true);
                        $res = [];
                        switch ($item['type']) {
                            case 'image':
                                $res[] = (new Cover('图片', 'data[img]', $data['img'] ?? '', $router->build('/psrphp/admin/tool/upload')));
                                $res[] = (new Input('链接地址', 'data[url]', $data['url'] ?? ''));
                                break;
                            case 'WYSIWYG':
                                $res[] = (new Summernote('内容', 'data[content]', $data['content'] ?? '', $router->build('/psrphp/admin/tool/upload')));
                                break;
                            case 'html':
                                $res[] = (new Code('HTML代码', 'data[html]', $data['html'] ?? ''));
                                break;
                            case 'tpl':
                                $res[] = (new Code('模板', 'data[tpl]', $data['tpl'] ?? ''))->set('help', '预设广告牌{$billboard}、广告{$item}变量');
                                break;
                            default:
                                break;
                        }
                        $res[] = (new Input('备注', 'tips', $item['tips']));
                        $res[] = (new Radio('是否发布', 'state', $item['state'], [
                            '0' => '否',
                            '1' => '是',
                        ]));
                        return $res;
                    })(),
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
            'data' => json_encode($request->post('data', []), JSON_UNESCAPED_UNICODE),
            'tips' => $request->post('tips'),
            'state' => $request->post('state'),
        ], [
            'id' => $item['id'],
        ]);
        return Response::success('操作成功！');
    }
}
