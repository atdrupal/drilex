<?php

namespace atphp\drilex\drupal;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class Drupal
{

    private $root;
    private $baseUrl;
    private $siteDir;
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

    public function boot()
    {
        global $conf;

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

    public function handle($path)
    {
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

        return new Response($title, drupal_render($output), $code, $messages);
    }

    public function loadEntity($type, $id)
    {
        return entity_load_single($type, $id);
    }

    public function getUser()
    {
        return $GLOBALS['user'];
    }

}
