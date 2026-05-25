<?php

namespace Phpify\Core;

use Phpify\Core\Http\Request;
use Phpify\Core\Http\Response;
use Phpify\Core\Routing\Router;
use Phpify\Core\View\Engine as ViewEngine;
use Phpify\Core\Database\Database;

class Application
{
    public static Application $app;
    public string $rootPath;
    public Router $router;
    public Request $request;
    public Response $response;
    public ViewEngine $view;
    protected array $config = [];

    public function __construct(string $rootPath, array $config = [])
    {
        self::$app = $this;
        $this->rootPath = $rootPath;
        $this->config = $config;
        $this->router = new Router();
        $this->request = new Request();
        $this->view = new ViewEngine($rootPath . '/app/Views', $rootPath . '/storage/cache');
        
        if (isset($config['db'])) {
            Database::connect($config['db']);
        }
    }

    public function run(): void
    {
        try {
            $resolved = $this->router->resolve($this->request);

            if (!$resolved) {
                (new Response())->setStatusCode(404)->setContent('404 Not Found')->send();
                return;
            }

            [$route, $params] = $resolved;
            $middleware = $route->getMiddleware();
            
            $this->handleMiddleware($middleware, $route, $params)->send();
        } catch (\Phpify\Core\Http\ValidationException $e) {
            if ($this->request->isJson()) {
                Response::json([
                    'message' => $e->getMessage(),
                    'errors' => $e->getErrors()
                ], 422)->send();
            } else {
                throw $e;
            }
        }
    }

    protected function handleMiddleware(array $middleware, $route, array $params): Response
    {
        $index = 0;

        $next = function (Request $request) use (&$index, $middleware, $route, $params, &$next) {
            if ($index < count($middleware)) {
                $mwClass = $middleware[$index++];
                $mw = new $mwClass();
                return $mw->handle($request, $next);
            }

            return $this->dispatch($route, $params);
        };

        return $next($this->request);
    }

    protected function dispatch($route, array $params): Response
    {
        $action = $route->getAction();

        if (is_callable($action)) {
            $content = call_user_func_array($action, array_merge([$this->request], $params));
            if ($content instanceof Response) return $content;
            return (new Response())->setContent((string)$content);
        }

        if (is_array($action)) {
            [$controllerClass, $method] = $action;
            $controller = new $controllerClass();
            $content = call_user_func_array([$controller, $method], array_merge([$this->request], $params));
            if ($content instanceof Response) return $content;
            return (new Response())->setContent((string)$content);
        }

        return (new Response())->setStatusCode(500)->setContent('Invalid action');
    }
}
