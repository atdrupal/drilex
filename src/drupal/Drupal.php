<?php

namespace atphp\drilex\drupal;

use atphp\drilex\drupal\api\ApiTrait;
use atphp\drilex\drupal\api\DrupalApiTrait;
use Doctrine\Common\Cache\Cache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class Drupal
{

    use DrupalApiTrait;

    /** @var Cache */
    private $cache;

    /** @var \Twig_Environment */
    private $twig;

    /** @var bool */
    private $booted = false;

    /** @var string */
    private $root;

    /** @var string */
    private $baseUrl;

    /** @var string */
    private $siteDir;

    /** @var array[] */
    private $conf;

    public function __construct($root, $siteDir, $baseUrl, array $conf = [])
    {
        $this->root = $root;
        $this->siteDir = $siteDir;
        $this->baseUrl = $baseUrl;
        $this->conf = $conf;

        $_SERVER['REMOTE_ADDR'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : pathinfo($baseUrl)['basename'];
        $_SERVER['HTTP_HOST'] = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : pathinfo($baseUrl)['basename'];
        $_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    }

    /**
     * @param \Twig_Environment $twig
     * @return self
     */
    public function setTwig(\Twig_Environment $twig)
    {
        $this->twig = $twig;
        return $this;
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    public function boot()
    {
        global $conf;

        if (!$this->booted && $this->booted = true) {
            define('DRUPAL_ROOT', $this->root);
            require_once DRUPAL_ROOT . '/includes/bootstrap.inc';

            $dir = &drupal_static('conf_path', 'sites/');
            $dir = $this->siteDir;

            if ($this->conf) {
                foreach ($this->conf as $k => $v) {
                    $GLOBALS[$k] = $v;
                }
            }

            drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
        }

        return $this;
    }

    public function handle(Request $request)
    {
        $path = trim($request->getRequestUri(), '/') ?: '<front>';
        $output = menu_execute_active_handler($path, false);
        $title = drupal_get_title();
        $code = 200;
        $messages = drupal_set_message() ?: [];

        switch ($output) {
            case MENU_NOT_FOUND:
                throw new NotFoundHttpException();
            case MENU_ACCESS_DENIED:
                throw new AccessDeniedException();
        }

        return new Response(
            $title,
            is_string($output) ? $output : drupal_render($output),
            $code,
            $messages
        );
    }

    public function getUser()
    {
        return isset($GLOBALS['user']) ? $GLOBALS['user'] : null;
    }

}
