<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Http\Stat;

use App\Psrphp\Ad\Psrphp\Script;
use App\Psrphp\Admin\Http\Common;
use PsrPHP\Database\Db;
use PsrPHP\Request\Request;
use PsrPHP\Template\Template;

class Index extends Common
{
    public function get(
        Db $db,
        Request $request,
        Template $template
    ) {
        $res = [
            'billboards' => $db->select('psrphp_ad_billboard', '*'),
            'items' => $db->select('psrphp_ad_item', '*'),
        ];

        if ($request->get('type', 'date') == 'date') {
            $date = $request->get('date', date('Y-m-d'));
            if ($item_id = $request->get('item_id', 0)) {
                $datas = [];
                for ($i = 0; $i < 24; $i++) {
                    $datas[$i] = [
                        'show' => $db->sum('psrphp_ad_show', 'times', [
                            'item_id' => $item_id,
                            'date' => $date,
                            'hour' => $i,
                        ]) ?: 0,
                        'click' => $db->count('psrphp_ad_click', [
                            'item_id' => $item_id,
                            'time[<>]' => [$date . ' ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT) . ':00:00', $date . ' ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT) . ':59:59'],
                        ]) ?: 0,
                        'title' => $date . ' ' . $i . '时',
                    ];
                }
            } else {
                $datas = [];
                for ($i = 0; $i < 24; $i++) {
                    $datas[$i] = [
                        'show' => $db->sum('psrphp_ad_show', 'times', [
                            'date' => $date,
                            'hour' => $i,
                        ]) ?: 0,
                        'click' => $db->count('psrphp_ad_click', [
                            'time[<>]' => [$date . ' ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT) . ':00:00', $date . ' ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT) . ':59:59'],
                        ]) ?: 0,
                        'title' => $date . ' ' . $i . '时',
                    ];
                }
            }
            $res['type'] = 'date';
            $res['date'] = $date;
        } else {
            $month = $request->get('month', date('Y-m'));
            $days =  (int)date('d', strtotime(date('Y-m-01 00:00:01', strtotime($month . '-15') + 86400 * 20)) - 100);
            if ($item_id = $request->get('item_id', 0)) {
                $datas = [];
                for ($i = 0; $i < $days; $i++) {
                    $date = date('Y-m-d', strtotime($month) + $i * 86400);
                    $datas[$i + 1] = [
                        'show' => $db->sum('psrphp_ad_show', 'times', [
                            'item_id' => $item_id,
                            'date' => $date,
                        ]) ?: 0,
                        'click' => $db->count('psrphp_ad_click', [
                            'item_id' => $item_id,
                            'time[<>]' => [$date . ' 00:00:00', $date . ' 23:59:59'],
                        ]) ?: 0,
                        'title' => $date,
                    ];
                }
            } else {
                $datas = [];
                for ($i = 0; $i < $days; $i++) {
                    $date = date('Y-m-d', strtotime($month) + $i * 86400);
                    $datas[$i + 1] = [
                        'show' => $db->sum('psrphp_ad_show', 'times', [
                            'date' => $date,
                        ]) ?: 0,
                        'click' => $db->count('psrphp_ad_click', [
                            'time[<>]' => [$date . ' 00:00:00', $date . ' 23:59:59'],
                        ]) ?: 0,
                        'title' => $date,
                    ];
                }
            }
            $res['type'] = 'month';
            $res['month'] = $month;
        }

        $max = 1;
        foreach ($datas as $value) {
            $max = max($max, $value['show'], $value['click']);
        }
        $res['datas'] = $datas;
        $res['max'] = $max;

        return $template->renderFromFile('stat/index@psrphp/ad', $res);
    }
}
