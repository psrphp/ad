<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Http\Billboard;

use App\Psrphp\Admin\Http\Common;
use PsrPHP\Database\Db;
use PsrPHP\Template\Template;

class Index extends Common
{
    public function get(
        Db $db,
        Template $template
    ) {
        return $template->renderFromFile('billboard/index@psrphp/ad', [
            'billboards' => $db->select('psrphp_ad_billboard', '*'),
        ]);
    }
}
