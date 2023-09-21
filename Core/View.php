<?php

namespace Core;

use \Twig\Loader\FilesystemLoader;

/**
 * View
 *
 * PHP version 7.4
 */
class View
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = "../App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */


    public static function renderTemplate($template, $args = [])
    {

        static $twig = null;

        if ($twig === null) {
            $loader = new FilesystemLoader('../App/Views');
            $twig = new \Twig\Environment($loader);
            $twig->addGlobal('is_logged_in', \App\Auth::isLoggedIn());
        }
        echo $twig->render($template, $args);
    }
}
