<?php
/*
    Vern Six MVC Framework version 3.0

    Copyright (c) 2007-2015 by Vernon E. Six, Jr.
    Author's websites: http://www.iPinga.com and http://www.VernSix.com

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to use
    the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice, author's websites and this permission notice
    shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
    IN THE SOFTWARE.
*/
defined('__VERN') or die('Restricted access');


/**
 * The router class is responsible for loading the correct controller.  It doesn't do anything else really.
 * Which controller to load comes from the URL. A typical url might look like this:  http://www.abc123.com/index.php?rt=news
 * or if you have htaccess amd mod_rewrite working it will be friendlier like http://www.example.com/news
 * As you can see, the route is in the rt variable with the value of 'news'.
 */
class v6_router
{

    private $path;
    public $file;
    public $controller;
    public $action;

    function __construct()
    {
    }

    /**
     * set controller directory path
     *
     * @param string $path
     *
     * @return void
     */
    public function setPath($path)
    {
        if (is_dir($path) == false) {
            throw new Exception ('Invalid controller path: `' . $path . '`');
        }
        $this->path = $path;
    }


    /**
     * load the controller
     * @access public
     * @return void
     */
    public function LaunchController()    {

        // check the route
        $this->getController();

        // if the file is not there diaf (http://www.urbandictionary.com/define.php?term=D.I.A.F.)
        if (is_readable($this->file) == false) {
            $this->file       = $this->path . '/error404.controller.php';
            $this->controller = 'error404';
        }

        // include the controller
        include $this->file;

        // a new controller class instance
        $class      = $this->controller . 'Controller';
        $controller = new $class;

        // check if the action is callable
        if (is_callable(array($controller, $this->action)) == false) {
            $action = 'index';
        } else {
            $action = $this->action;
        }

        // run the action
        $controller->$action();

    }


    /**
     * This method call does the work of loading the controller.
     * It gets the route variable from the url via $_GET['rt'] it is able to check if a contoller was loaded, and if not
     * it will default to index.  It also checks if an action was loaded.  An action is a method within the specified
     * controller.  If no action has been declared, it defaults to index.
     *
     * The controller is the first part of the url and the action within that controller is the second part of the
     * url. ie: http://www.mysite.com/news/show   News would refer to the controller and show would refer to the action
     * within that controller.
     * @return void
     */
    private function getController()
    {

        // get the route from the url. htaccess file create the rt querystring
        if (empty($_GET['rt']) == true) {
            $route = '';
        } else {
            $route = $_GET['rt'];
        }

        if (empty($route)) {
            $route = 'index';
        } else {
            // get the parts of the route
            $uri_segments     = explode('/', $route);
            $this->controller = $uri_segments[0];
            if (isset($uri_segments[1])) {
                $this->action = $uri_segments[1];
            }
        }

        if (empty($this->controller)) {
            $this->controller = 'index';
        }

        // Get action
        if (empty($this->action)) {
            $this->action = 'index';
        }

        // set the file path
        $this->file = $this->path . DS . $this->controller . '.controller.php';

        // die($route .'...'. $this->file .'...'. $this->action);
    }


}

?>