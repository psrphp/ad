<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Http\Stat;

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
        $month = $request->get('month', date('Y-m'));
        $days =  (int)date('d', strtotime(date('Y-m-01 00:00:01', strtotime($month . '-15') + 86400 * 20)) - 100);

        if ($item_id = $request->get('item_id', 0)) {
            $datas = [];
            for ($i = 0; $i < $days; $i++) {
                $date = date('Y-m-d', strtotime($month) + $i * 86400);
                $datas[$date] = [
                    'show' => $db->sum('psrphp_ad_show', 'times', [
                        'item_id' => $item_id,
                        'date' => $date,
                    ]) ?: 0,
                    'click' => $db->count('psrphp_ad_click', [
                        'item_id' => $item_id,
                        'time[<>]' => [$date . ' 00:00:00', $date . ' 23:59:59'],
                    ]) ?: 0,
                ];
            }
        } else {
            $datas = [];
            for ($i = 0; $i < $days; $i++) {
                $date = date('Y-m-d', strtotime($month) + $i * 86400);
                $datas[$date] = [
                    'show' => $db->sum('psrphp_ad_show', 'times', [
                        'date' => $date,
                    ]) ?: 0,
                    'click' => $db->count('psrphp_ad_click', [
                        'time[<>]' => [$date . ' 00:00:00', $date . ' 23:59:59'],
                    ]) ?: 0,
                ];
            }
        }

        $max = 0;
        foreach ($datas as $value) {
            $max = max($max, $value['show'], $value['click']);
        }

        return $template->renderFromFile('stat/index@psrphp/ad', [
            'month' => $month,
            'datas' => $datas,
            'max' => $max,
            'billboards' => $db->select('psrphp_ad_billboard', '*'),
            'items' => $db->select('psrphp_ad_item', '*'),
        ]);
    }
}
