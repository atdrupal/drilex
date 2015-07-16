<?php

namespace atphp\drilex;

use atphp\drilex\drupal\Drupal;
use atphp\drilex\traits\GetSetTrait;
use Silex\Application;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler;

class App extends Application
{

    use GetSetTrait;

    private $drupal;

    public function __construct(array $values = [])
    {
        parent::__construct($values + ['app.root' => dirname(__DIR__)]);

        $this->register(new ServiceProvider());
        $this->mount('/', new ControllerProvider());
    }

    public function drupal()
    {
        if (null === $this->drupal) {
            $this->drupal = new Drupal(
                $this['drupal']['root'],
                $this['drupal']['site_dir'],
                $this['drupal']['base_url'],
                $this['drupal']['settings']
            );
            $this->drupal->boot();
        }

        return $this->drupal;
    }

    public function getUser()
    {
        return $this->drupal()->getUser();
    }

    public function boot()
    {
        parent::boot();

        // Convert json body to array structure
        #$this->before(function (Request $request) {
        #    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        #        $data = json_decode($request->getContent(), true);
        #        $request->request->replace(is_array($data) ? $data : array());
        #    }
        #});

        // Custom error handler page, it's must be placed before any route definition.
        // Customize error template by error code by create template: pages/error/{404,500,..}.twig
        $this->error(function (\Exception $e) {
            $template = 'pages/error/default.twig';
            $exception = FlattenException::create($e);
            $code = $exception->getStatusCode();
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
