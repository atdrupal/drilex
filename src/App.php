<?php

namespace vendor_name\project_name;

use Silex\Application;
use vendor_name\project_name\traits\GetSetTrait;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler;

class App extends Application
{

    use GetSetTrait;

    public function __construct(array $values = [])
    {
        parent::__construct($values + ['app.root' => dirname(__DIR__)]);
        
        $this->register(new ServiceProvider());
        $this->mount('/', new ControllerProvider());
    }
    
    public function boot()
    {
        parent::boot();
        
        // Custom error handler page, it's must be placed before any route definition.
        // Customize error template by error code by create template: pages/error/{404,500,..}.twig
        $this->error(function (\Exception $e) {
            $template = 'pages/error/default.twig';
            $code = $e->getStatusCode();
            $exception = FlattenException::create($e);
            $handler = new ExceptionHandler($this['debug']);

            if ($this->getTwig()->getLoader()->exists("pages/error/$code.twig")) {
                $template = "pages/error/$code.twig";
            }

            return $this->getTwig()->render($template, [
                'exception'  => $exception,
                'stylesheet' => $handler->getStylesheet($exception),
                'content'    => $handler->getContent($exception),
            ]);
        });
    }

}
