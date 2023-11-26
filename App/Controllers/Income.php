<?php

namespace App\Controllers;

use Core\View;

/**
 * Add & edit income controller
 * 
 * PHP version 8.2
 */
class Income extends Authenticated {

    /**
     * Show add income form
     * 
     * @return void
     */
    public function newAction() {

        View::renderTemplate('Income/new.html');
    }
}
