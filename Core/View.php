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


     public static function renderTemplate($template, $args = []) {

        echo static::getTemplate($template, $args);
    }

    /**
     * Get the contents of a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */

     public static function getTemplate($template, $args = []) {

        static $twig = null;

        if ($twig === null) {
            $loader = new FilesystemLoader('../App/Views');
            $twig = new \Twig\Environment($loader, [
                    'debug' => true
            ]);
            $twig->addExtension(new \Twig\Extension\DebugExtension());
            $twig->addGlobal('current_user', \App\Auth::getUser());
            $twig->addGlobal('current_url', \App\Auth::rememberRequestedPage());
            $twig->addGlobal('flash_messages', \App\Flash::getMessages());
            $twig->addGlobal('currentDate', \App\Date::getCurrentDate());
            $twig->addGlobal('income', \App\Settings::loadUserIncomeNames());
            $twig->addGlobal('expense', \App\Settings::loadUserExpenseNames());
            $twig->addGlobal('payment', \App\Settings::loadUserPaymentMethods());
        }

        return $twig->render($template, $args);
    }
}
