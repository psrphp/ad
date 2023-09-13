<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Http\Item;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Database\Db;
use PsrPHP\Form\Builder;
use PsrPHP\Form\Col;
use PsrPHP\Form\Row;
use PsrPHP\Form\Code;
use PsrPHP\Form\Cover;
use PsrPHP\Form\Input;
use PsrPHP\Form\Hidden;
use PsrPHP\Form\Radio;
use PsrPHP\Form\Radios;
use PsrPHP\Form\Summernote;
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
                    (new Input('标题', 'title', $item['title']))->setRequired(),
                    ...(function () use ($router, $item): array {
                        $data = json_decode($item['data'], true);
                        $res = [];
                        switch ($item['type']) {
                            case 'image':
                                $res[] = (new Cover('图片', 'data[img]', $data['img'] ?? '', $router->build('/psrphp/admin/tool/upload')));
                                $res[] = (new Input('链接地址', 'data[url]', $data['url'] ?? ''))->setType('url');
                                break;
                            case 'WYSIWYG':
                                $res[] = (new Summernote('内容', 'data[content]', $data['content'] ?? '', $router->build('/psrphp/admin/tool/upload')));
                                break;
                            case 'html':
                                $res[] = (new Code('HTML代码', 'data[html]', $data['html'] ?? ''));
                                break;
                            case 'tpl':
                                $res[] = (new Code('模板', 'data[tpl]', $data['tpl'] ?? ''))->setHelp('预设广告牌{$billboard}、广告{$item}变量');
                                break;
                            default:
                                break;
                        }
                        $res[] = (new Input('最大展示量', 'max_showtimes', $item['max_showtimes']))
                            ->setType('number')
                            ->setStep(1)
                            ->setMax(9999999999)
                            ->setMin(0)
                            ->setHelp('超过最大展示量会停止展示该广告');
                        $res[] = (new Input('最大点击量', 'max_click', $item['max_click']))
                            ->setType('number')
                            ->setStep(1)
                            ->setMax(9999999999)
                            ->setMin(0)
                            ->setHelp('超过最大点击量会停止展示该广告');
                        $res[] = (new Input('开始展示时间', 'starttime', $item['starttime']))
                            ->setType('datetime-local')
                            ->setHelp('在开始时间和截至时间之内的广告才会展示');
                        $res[] = (new Input('截止展示时间', 'endtime', $item['endtime']))
                            ->setType('datetime-local')
                            ->setHelp('在开始时间和截至时间之内的广告才会展示');
                        $res[] = (new Radios('pc端显示'))->addRadio(
                            new Radio('否', 'pc', '0', $item['pc'] == 0),
                            new Radio('是', 'pc', '1', $item['pc'] == 1)
                        );
                        $res[] = (new Radios('移动端显示'))->addRadio(
                            new Radio('否', 'mobile', '0', $item['mobile'] == 0),
                            new Radio('是', 'mobile', '1', $item['mobile'] == 1)
                        );
                        $res[] = (new Radios('是否发布'))->addRadio(
                            new Radio('否', 'state', '0', $item['state'] == 0),
                            new Radio('是', 'state', '1', $item['state'] == 1)
                        );
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
            'pc' => $request->post('pc'),
            'mobile' => $request->post('mobile'),
            'state' => $request->post('state'),
        ], [
            'id' => $item['id'],
        ]);
        return Response::success('操作成功！');
    }
}
