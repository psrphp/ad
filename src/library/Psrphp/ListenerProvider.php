<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Psrphp;

use App\Psrphp\Ad\Http\Billboard\Index;
use App\Psrphp\Admin\Model\MenuProvider;
use PsrPHP\Psr11\Container;
use PsrPHP\Template\Template;
use PsrPHP\Framework\Listener;

class ListenerProvider extends Listener
{

    public function __construct()
    {
        $this->add(Container::class, function (
            Container $container
        ) {
            $container->set(Template::class, function (
                Template $template
            ) {
                $template->extend('/\{ad\s*([a-zA-Z0-9_]+)\s*\}/Ui', function ($matchs) {
                    return '<?php echo \App\Psrphp\Ad\Model\Ad::render(\'' . $matchs[1] . '\') ?>';
                });
                return $template;
            });
        });

        $this->add(MenuProvider::class, function (
            MenuProvider $provider
        ) {
            $provider->add('广告管理', Index::class);
        });
    }
}
