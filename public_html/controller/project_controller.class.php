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

define( 'V6_OK',                        0 );
define( 'V6_RECORD_NOT_FOUND',          1 );
define( 'V6_INCOMPLETE_DATA',           2 );
define( 'V6_DUPLICATE_RECORD',          3 );
define( 'V6_AUTHENTICATION_FAILURE',    4 );
define( 'V6_INVALID_HOST',              5 );


Abstract class project_Controller extends v6_controller
{

    /**
     * @var array $json
     */
    public $json = array();

    public function __construct()
    {
        parent::__construct();

        // make sure every return of json has certain elements. You can add others or change these, but these are required!
        $this->json['timer']    = array('start'=>(float)microtime(true),'end'=>0);
        $this->json['status']   = array('code'=>V6_OK,'message'=>'OK');
        $this->json['data']     = array();

        /*
        // make sure the request is ONLY coming from my IP address (obviously you would remove this bit)
        if ( $_SERVER['SERVER_ADDR'] != '127.0.0.1' ) {
            $this->json['status']['code']       = V6_INVALID_HOST;
            $this->json['status']['message']    = 'Invalid Host!';
            $this->SendJSON();
        }
        */

        // stuff that should be removed in production
        $this->json['remove_in_production'] = array();
        $this->json['remove_in_production']['debug'] = array();
        $this->json['remove_in_production']['post']     = $_POST;
        $this->json['remove_in_production']['get']      = $_GET;
        $this->json['remove_in_production']['server']   = $_SERVER;
        $this->json['remove_in_production']['request']  = $_REQUEST;
        $this->json['remove_in_production']['env']      = $_ENV;
        $this->json['remove_in_production']['cookie']   = $_COOKIE;


    }

    /**
     * Shorthand way of writing things to the debug log
     * @param string $message
     */
    public function debug($message='')
    {
        $this->json['remove_in_production']['debug'][] = date('Y-m-d H:i:s'). ' - '. $message;
    }



    /**
     * Send the current contents of the json array to client app
     */
    public function SendJSON() {
        global $registry;

        // The registry log array allows functions, that are out of scope, to at least communicate to the log
        $this->json['remove_in_production']['registry_log'] = $registry->log;

        $this->json['timer']['end'] = (float)microtime(true);
        $this->json['timer']['elapsed'] = $this->json['timer']['end'] - $this->json['timer']['start'];
        // ob_end_clean();

        // 12/28/2014 - I don't recall why, but I had to comment out the following line to make it work with some browsers and clients
        header ("Content-Type:text/json");		// case is crucial!

        // TODO: add logging to database

        echo json_encode($this->json);
        exit();
    }


    public function default_post_value($var_name,$default_value) {
        if ( (isset($_POST[$var_name])==true) && (empty($_POST[$var_name])==false) ) {
            return $_POST[$var_name];
        }
        return $default_value;
    }

    public function is_logged_in() {
        return (v6_cookie::keyvalue('user_id') > 0);
    }



}
