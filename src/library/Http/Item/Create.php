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
use PsrPHP\Form\Radio;
use PsrPHP\Form\Radios;
use PsrPHP\Form\Summernote;
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
        $type = $request->get('type');

        $form = new Builder('添加广告');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Input('billboard_id', 'billboard_id', $billboard['id']))->setType('hidden'),
                    (new Input('type', 'type', $type))->setType('hidden'),
                    (new Input('标题', 'title'))->setRequired(true),
                    ...(function () use ($type, $router): array {
                        $res = [];
                        switch ($type) {
                            case 'image':
                                $res[] = (new Cover('图片', 'data[img]', null, $router->build('/psrphp/admin/tool/upload')));
                                $res[] = (new Input('链接地址', 'data[url]', null))->setType('url');
                                break;
                            case 'WYSIWYG':
                                $res[] = (new Summernote('内容', 'data[content]', null, $router->build('/psrphp/admin/tool/upload')));
                                break;
                            case 'html':
                                $res[] = (new Code('HTML代码', 'data[html]'));
                                break;
                            case 'tpl':
                                $res[] = (new Code('模板', 'data[tpl]'))->setHelp('预设广告牌{$billboard}、广告{$item}变量');
                                break;
                            default:
                                break;
                        }
                        $res[] = (new Input('最大展示量', 'max_showtimes', 500000))
                            ->setType('number')
                            ->setStep(1)
                            ->setMax(9999999999)
                            ->setMin(0)
                            ->setHelp('超过最大展示量会停止展示该广告');
                        $res[] = (new Input('最大点击量', 'max_click', 500000))
                            ->setType('number')
                            ->setStep(1)
                            ->setMax(9999999999)
                            ->setMin(0)
                            ->setHelp('超过最大点击量会停止展示该广告');
                        $res[] = (new Input('开始展示时间', 'starttime', date('Y-m-d H:i:s')))
                            ->setType('datetime-local')
                            ->setHelp('在开始时间和截至时间之内的广告才会展示');
                        $res[] = (new Input('截止展示时间', 'endtime', date('Y-m-d H:i:s', time() + 86400 * 30)))
                            ->setType('datetime-local')
                            ->setHelp('在开始时间和截至时间之内的广告才会展示');
                        $res[] = (new Radios('是否发布'))->addRadio(
                            new Radio('否', 'state', '0', false),
                            new Radio('是', 'state', '1', true)
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
        Request $request
    ) {
        $billboard = $db->get('psrphp_ad_billboard', '*', [
            'id' => $request->post('billboard_id'),
        ]);

        $db->insert('psrphp_ad_item', [
            'billboard_id' => $billboard['id'],
            'title' => $request->post('title'),
            'type' => $request->post('type'),
            'max_showtimes' => $request->post('max_showtimes', 99999999),
            'max_click' => $request->post('max_click', 99999999),
            'starttime' => $request->post('starttime'),
            'endtime' => $request->post('endtime'),
            'data' => json_encode($request->post('data', []), JSON_UNESCAPED_UNICODE),
            'state' => $request->post('state'),
        ]);

        return Response::success('操作成功！');
    }
}
