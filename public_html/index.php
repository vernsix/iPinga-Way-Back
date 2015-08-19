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

// You have to manually include these.  Your path will most likely be differnt
// require_once '/php/pear/Mail.php';
// require_once '/php/pear/Mail/mime.php';
require_once '/usr/local/lib/php/Mail.php';
require_once '/usr/local/lib/php/Mail/mime.php';


// I just use this to make quick changes to the path when I deploy elsewhere.
define('rootpath', '/home/ipinga/public_html/dev/');

set_time_limit(120); // 2 minutes = way long!

$vern_config = array(
    'encryption' => array(
        'key' => 'bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3'
    ),
    'error' => array(
        'logfile' => rootpath . 'applogs/php-errors.php',
        'display' => true
    ),
    'debug' => array(
        'debug' => true,
        'logfile' => rootpath . 'applogs/debug.php',
        'immediate' => true
    ),
    'smtp' => array(
        'host' => 'mail.clienttestzone.com',
        'port' => 26,
        'auth' => true,
        'username' => 'tester@clienttestzone.com',
        'password' => 'tester123$',
        'debug' => false,
        'timeout' => 15,
        'localhost' => 'mail.clienttestzone.com'
    ),
    'mysql' => array(
        'host' => 'localhost',
        'database' => 'ipinga_skeleton',
        'user' => 'ipinga_skeleton',
        'password' => 'DLQSNR!3KlP3'
    ),
//    'session' => array(
//        'save_path' => '/somepath/php-sessions',
//        'name'      => 'I_WISH_I_COULD_BE_LIKE_VERN_SIX'
//    ),
    'path' => array(
        'root' => realpath(dirname(__FILE__)),
        'framework' => rootpath . 'iPinga3'
    ),
    'time' => array(
        'timezone' => 'America/Chicago'
    ),
    'login' => array(
        'max_minutes' => 10,
        'login_url' => '/index/login',
        'expired_url' => '/index/logout_timed_out',
        'ip_changed_url' => '/index/logout_ip_changed'
    ),
    'cookie' => array(
        'name' => 'v6_cookie',
        'expiration_time' => time() + (60 * 60 * 24 * 30)     //   30 days from now
    )
);

include_once $vern_config['path']['framework'] . '/start_vern.php';

?>