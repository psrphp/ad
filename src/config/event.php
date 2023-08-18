<?php

use PsrPHP\Framework\App;
use PsrPHP\Psr11\Container;
use PsrPHP\Template\Template;

return [
    App::class => [
        function (
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
        },
    ],
];
