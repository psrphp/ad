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
                    (new Input('标题', 'title', $item['title']))->set('required', 'required'),
                    ...(function () use ($router, $item): array {
                        $data = json_decode($item['data'], true);
                        $res = [];
                        switch ($item['type']) {
                            case 'image':
                                $res[] = (new Cover('图片', 'data[img]', $data['img'] ?? '', $router->build('/psrphp/admin/tool/upload')));
                                $res[] = (new Input('链接地址', 'data[url]', $data['url'] ?? ''));
                                $res[] = (new Input('最大点击量', 'max_click', 500000))
                                    ->set('type', 'number')
                                    ->set('step', 1)
                                    ->set('max', 9999999999)
                                    ->set('min', 0)
                                    ->set('help', '超过最大点击量会停止展示该广告');
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
                        $res[] = (new Input('最大展示量', 'max_showtimes', $item['max_showtimes']))
                            ->set('type', 'number')
                            ->set('step', 1)
                            ->set('max', 9999999999)
                            ->set('min', 0)
                            ->set('help', '超过最大展示量会停止展示该广告');
                        $res[] = (new Input('开始展示时间', 'starttime', $item['starttime']))
                            ->set('type', 'datetime-local')
                            ->set('help', '在开始时间和截至时间之内的广告才会展示');
                        $res[] = (new Input('截止展示时间', 'endtime', $item['endtime']))
                            ->set('type', 'datetime-local')
                            ->set('help', '在开始时间和截至时间之内的广告才会展示');
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
            'title' => $request->post('title'),
            'data' => json_encode($request->post('data', []), JSON_UNESCAPED_UNICODE),
            'max_showtimes' => $request->post('max_showtimes', 99999999),
            'max_click' => $request->post('max_click', 99999999),
            'starttime' => $request->post('starttime'),
            'endtime' => $request->post('endtime'),
            'state' => $request->post('state'),
        ], [
            'id' => $item['id'],
        ]);
        return Response::success('操作成功！');
    }
}
