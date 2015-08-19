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

/* changes

6/3/2015 - I basically rewrote this entire object to no longer use the $_SESSION variable, but to instead us v6_Cookie


*/


/** session manager - makes certain the user is logged in, etc. */
class v6_manager
{

    /** @var int */
    public $max_minutes = 10;
    /** @var string */
    public $login_url = '/home';
    /** @var string */
    public $expired_url = '/logout/timed_out';
    /** @var string */
    public $ip_changed_url = '/logout/ip_changed';
    /** @var string what we store in the session for the user id */
    public $user_id_keyname = 'user_id';

    /** @var string */
    public $new_url = '';

    /** @var bool */
    public $IsLoggedIn = false;

    /** @var string */
    public $message = '';

    /** @var array */
    public $logged_in_details = array();

    /**
     * @param array $params
     */
    function __construct($params = array())
    {
        global $vern_config;
        if (isset($vern_config['login'])) {
            if (isset($vern_config['login']['max_minutes'])) {
                $this->max_minutes = $vern_config['login']['max_minutes'];
            }
            if (isset($vern_config['login']['login_url'])) {
                $this->login_url = $vern_config['login']['login_url'];
            }
            if (isset($vern_config['login']['expired_url'])) {
                $this->expired_url = $vern_config['login']['expired_url'];
            }
            if (isset($vern_config['login']['ip_changed_url'])) {
                $this->ip_changed_url = $vern_config['login']['ip_changed_url'];
            }
        }

        // params override global settings
        if (isset($params['max_minutes'])) {
            $this->max_minutes = $params['max_minutes'];
        }
        if (isset($params['login_url'])) {
            $this->login_url = $params['login_url'];
        }
        if (isset($params['expired_url'])) {
            $this->expired_url = $params['expired_url'];
        }
        if (isset($params['ip_changed_url'])) {
            $this->ip_changed_url = $params['ip_changed_url'];
        }

        $this->new_url = '';
    }

    private function load_from_cookie()
    {
        if (v6_cookie::keyexists('logged_in_details') == true) {
            $this->logged_in_details = v6_cookie::keyvalue('logged_in_details');
        } else {
            $this->logged_in_details = array();
        }
    }

    private function shutdown()
    {
        v6_cookie::add('logged_in_details', $this->logged_in_details);
    }


    function logout()
    {
        $this->logged_in_details = array();
        $this->IsLoggedIn = false;
        $this->shutdown();
    }


    /**
     * @param int $UserId
     */
    function update($UserId)
    {
        $this->load_from_cookie();
        $this->logged_in_details['LAST_ACTIVITY'] = strtotime("now");
        $this->logged_in_details['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
        $this->logged_in_details['USER_ID'] = $UserId;
        $this->shutdown();
    }


    /**
     * @param bool|true $redirect_if_not_logged_in   If true and the user is not logged in, then redirect elsewhere right now
     *
     * @return bool $isLoggedIn
     */
    function UserIsLoggedIn($redirect_if_not_logged_in = true)
    {
        $this->load_from_cookie();

        $this->new_url = '';    // just in case we are called twice by mistake, start over.  It happens.

        if ( !isset($this->logged_in_details['USER_ID']) ) {
            $this->new_url = $this->login_url;
            $this->message = 'You are not logged in';
        } else {

            $current_time   = strtotime("now");
            $last_time      = $this->logged_in_details['LAST_ACTIVITY'];
            $difference     = $current_time - $last_time;

            if ($difference > ($this->max_minutes * 60)) {
                $this->new_url = $this->expired_url;
                $this->message = 'You have been logged out due to inactivity';
            } else {
                if (!$this->logged_in_details['REMOTE_ADDR'] == $_SERVER['REMOTE_ADDR']) {
                    $this->new_url = $this->ip_changed_url;
                    $this->message = 'You have been logged out because your ip address changed';
                }
            }

        }

        if (empty($this->new_url)) {
            $this->IsLoggedIn = true;
            $this->shutdown();
        } else {

            $this->IsLoggedIn = false;

            if ($redirect_if_not_logged_in) {
                $this->logged_in_details = array();
                $this->shutdown();
                header('location: ' . $this->new_url);
                exit();
            }
            $this->shutdown();

        }

        return $this->IsLoggedIn;

    }

}

?>