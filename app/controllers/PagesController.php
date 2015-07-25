<?php

/**
 * Handle misc. pages.
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class PagesController extends \BaseController {

    /**
     * Temporary hompage.
     *
     * @return Response
     */
    public function getHomepage()
    {
        return View::make('homepage');
    }

    /**
     * Sort of menu for the user.
     *
     * @return Response
     */
    public function getMenu()
    {
        return View::make('menu');
    }

}
