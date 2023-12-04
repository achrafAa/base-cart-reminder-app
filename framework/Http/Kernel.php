<?php

namespace Achraf\framework\Http;

use Achraf\framework\Container\Container;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

readonly class Kernel
{
    /**
     * @param  Container  $container
     */
    public function __construct(private Container $container)
    {
    }

    /**
     * @param  Request  $request
     * @return Response
     * @throws \ReflectionException
     */
    public function handle(Request $request): Response
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
            foreach (include BASE_PATH . '/routes/web.php' as $route) {
                $routeCollector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPathInfo(),
        );

        $status = $routeInfo[0] ?? 0;
        [$controller, $method] = $routeInfo[1] ?? [null, null];
        $vars = $routeInfo[2] ?? [];

        return match ($status) {
            Dispatcher::NOT_FOUND => new Response('Not Found', 404),
            Dispatcher::METHOD_NOT_ALLOWED => new Response('Method Not Allowed', 405),
            Dispatcher::FOUND =>call_user_func_array([$this->container->get($controller), $method], $vars)
        };
    }
}
