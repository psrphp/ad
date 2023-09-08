<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Psrphp;

use App\Psrphp\Ad\Http\Billboard\Index;
use App\Psrphp\Ad\Http\Click\Index as ClickIndex;
use App\Psrphp\Ad\Http\Stat\Index as StatIndex;
use App\Psrphp\Ad\Http\Web\Jump;
use App\Psrphp\Admin\Model\MenuProvider;
use Psr\EventDispatcher\ListenerProviderInterface;
use PsrPHP\Framework\Framework;
use PsrPHP\Psr11\Container;
use PsrPHP\Router\Router;
use PsrPHP\Template\Template;

class ListenerProvider implements ListenerProviderInterface
{
    public function getListenersForEvent(object $event): iterable
    {
        if (is_a($event, Container::class)) {
            yield function () use ($event) {
                Framework::execute(function (
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
                }, [
                    Container::class => $event,
                ]);
            };
        }

        if (is_a($event, Router::class)) {
            yield function () use ($event) {
                Framework::execute(function (
                    Router $router,
                ) {
                    $router->addGroup($router->getSiteRoot(), function (Router $router) {
                        $router->addRoute(['GET'], '/jump', Jump::class, [], [], '/psrphp/ad/web/jump');
                    });
                }, [
                    Router::class => $event
                ]);
            };
        }

        if (is_a($event, MenuProvider::class)) {
            yield function () use ($event) {
                Framework::execute(function (
                    MenuProvider $provider
                ) {
                    $provider->add('广告管理', Index::class);
                    $provider->add('广告点击记录', ClickIndex::class);
                    $provider->add('广告统计', StatIndex::class);
                }, [
                    MenuProvider::class => $event,
                ]);
            };
        }
    }
}
