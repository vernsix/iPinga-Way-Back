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
global $vern_config;

/* php acts stupid without setting the timezone */
date_default_timezone_set($vern_config['time']['timezone']);

ini_set('log_errors',true);
ini_set("error_log", $vern_config['error']['logfile']);
ini_set('display_errors', $vern_config['error']['display']);


// We need to use output buffering to make sure any cookies that are set in the code get handled properly.
// ie: sent in the header instead of inline with the html, etc as they are generated.
ob_start();

register_shutdown_function('handleShutdown');


/**********************************************************************************************************
 * Setup all the session stuff.  This is crucial to avoid hackers as much as possible.
 *********************************************************************************************************/
if (isset($vern_config['session']['save_path']) == true) {

    session_save_path($vern_config['session']['save_path']);

    // 12/26/2014 - Everything below here used to be outside of this IF statement.  I decided today that if
    // $vern_config['session']['save_path'] doesn't contain a path, then the programmer doesn't want sessions
    // initialized, etc.

    session_name($vern_config['session']['name']);
    session_start();

    // prevent session fixation attacks...
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id();
        $_SESSION['initiated'] = true;
    }

    /*
     * prevent session hijacking...
     *
     * this is a simple little trick to make sure a user doesn't create a session
     * with a normal browser, then launch his hack program.  If he doesn't have the precise
     * same user_agent, then he will fail this test and hit a dead end.  Obviously this could
     * be bypassed, but not nearly as easy as you might think.
     *
     */
    $clear_text_fingerprint = 'anything that is consistent can go here';
    if ((isset($_SESSION['FINGERPRINT'])) && (isset($_SERVER['HTTP_USER_AGENT']))) {
        $clear_text_fingerprint .= $_SERVER['HTTP_USER_AGENT'];
        if ($_SESSION['FINGERPRINT'] != md5($clear_text_fingerprint)) {
            session_unset();
            die('Session data corrupted.  Please start again.');
        }
    } else {
        // this branch of the if...else is where we go the first time...
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clear_text_fingerprint .= $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['FINGERPRINT'] = md5($clear_text_fingerprint);
        } else {
            $_SESSION['FINGERPRINT'] = md5(time());
        }
    }

} // isset($vern_config['session']['save_path'])



/**********************************************************************************************************
 * Advertising tracking system
 *********************************************************************************************************/

/*
    TODO:  8/5/15 - This is a horrible place to put this.  99.99% of the time this isn't used.  It
    should be put in project controller if the site needs it instead of forcing it on every system
    that uses this framework!
*/

/**
 * Advertisement Tracking is handled by putting the ad query string in a cookie
 *
 * Let's say you have a webapp that you want to allow your affiliate marketers to help you promote it.
 * You assign them each an "advertiser_id" which is a numeric value.   Let's further say your url is
 * http://www.FancyWebApp.com/    Then the link in code for each advertiser would be something like this
 * http://www.FancyWebApp.com/?ad=512   where 512 is their advertiser_id.   In your app code you simply
 * have to reference the value of the global variable declared here... $advertiser_id
 */
/*
$advertiser_id      = '0';
$cookie_expire_time = time() + (60 * 60 * 24 * 30);

// was it passed as part of the query string?
if (isset($_GET['ad']) == true) {
    // I only allow numbers so I wanna make sure it's numeric instead of some hacker trying to blow me up
    if (is_numeric($_GET['ad'])) {
        setcookie('a', $_GET['ad'], $cookie_expire_time);
        // even though we set it as a cookie, it is NOT in the $_COOKIE[] array this time.
        // It only gets put in $_COOKIE[] when we get it back from the browser (ie: on subsequent page loads)
        $advertiser_id = $_GET['ad'];
    } else {
        setcookie('a', '', 1); // expire the cookie NOW
        $advertiser_id = '0'; // no advertiser
    }
} else {
    // was it in the cookie?
    if (isset($_COOKIE['a'])) {
        if (is_numeric($_COOKIE['a'])) {
            setcookie('a', $_COOKIE['a'], $cookie_expire_time);
            $advertiser_id = $_COOKIE['a'];
        } else {
            setcookie('a', '', 1); // expire the cookie NOW
            $advertiser_id = '0'; // no advertiser
        }
    } else {
        setcookie('a', '', 1); // expire the cookie NOW
        $advertiser_id = '0'; // no advertiser
    }
}
*/

/**********************************************************************************************************
 * Load the actual framework...
 *********************************************************************************************************/
define('DS', DIRECTORY_SEPARATOR);                          // needed for linux vs windows

// Have to tell the framework where we are now...
define ('__WEBSITE_ROOT_PATH', $vern_config['path']['root']);
define ('__IPINGA_CODE_PATH', $vern_config['path']['framework']);
define ('__VERN', true); // without this, all the code should die. keeps us safe


/**********************************************************************************************************
 * initialize the debug logger
 *********************************************************************************************************/
include_once __IPINGA_CODE_PATH . DS . 'v6_debug.class.php';

// use debugging mode or ignore?
v6_debug::$debug     = $vern_config['debug']['debug'];
v6_debug::$immediate = $vern_config['debug']['immediate'];

// 12/26/2014 - You can now set a name for the textfile in the $vern_config variable
if (isset($vern_config['debug']['logfile']) == true) {
    v6_debug::$textfile = $vern_config['debug']['logfile'];
}
// 12/26/2014 - Write the requested url to the debug system
if (isset($_GET['rt']) == true) {
    v6_debug::log('');
    v6_debug::log('Requested: ' . $_GET['rt']);
}

/**********************************************************************************************************
 * initialize the error system
 *********************************************************************************************************/

// use the framework's error handler. Comment these lines out if not.
include_once __IPINGA_CODE_PATH . DS . 'v6_error_handler.php';

// set error reporting level
error_reporting(E_ALL & ~E_NOTICE);
// error_reporting(E_ERROR);

// tell PHP's error system which errors to send to my error system
set_error_handler('v6_error_handler', E_ALL & ~E_NOTICE);

// Tell the error system which errors to die on
v6_error_fatal(E_ALL^E_NOTICE); // will die on` any error except E_NOTICE

v6_error_fatal(E_ALL); // will die on any error

/**********************************************************************************************************
 * misc
 *********************************************************************************************************/
include_once __IPINGA_CODE_PATH . DS . 'ajax_responses.php';

include_once __IPINGA_CODE_PATH . DS . 'functions.php';

// comment this line out if you are not using jqueryui
// include_once __IPINGA_CODE_PATH . DS . 'jqueryui_functions.php';

// the global suitcase
$registry = new v6_registry;

// 12/28/2014 -
v6_cookie::initialize();



/**********************************************************************************************************
 * configure databases.  Comment out what you don't need
 *********************************************************************************************************/

// mysql
$registry->db = new PDO('mysql:host=' . $vern_config['mysql']['host'] . ';dbname=' . $vern_config['mysql']['database'], $vern_config['mysql']['user'], $vern_config['mysql']['password']);
$registry->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// mongo
// $registry->MongoConn = new Mongo('mongodb://'.$vern_config['mongo']['user'].':'.$vern_config['mongo']['password'].'@'.$vern_config['mongo']['host'].':'.$vern_config['mongo']['port'].'/'.$vern_config['mongo']['database'] );
// $registry->MongoDB = $registry->MongoConn->selectDB($vern_config['mongo']['database']);


// load any programmer appcode functions, etc
include_once __WEBSITE_ROOT_PATH . DS . 'includes' . DS . 'appcode.php';


/**********************************************************************************************************
 * load the router and launch the controller
 *********************************************************************************************************/
try {
    $registry->router = new v6_router();
    $registry->router->setPath(__WEBSITE_ROOT_PATH . DS . 'controller');
} catch (Exception $e) {
    echo 'Caught exception trying to create router: ', $e->getMessage(), "\n";
    v6_debug::log('Caught exception trying to create router: ', $e->getMessage());
}

// now hand processing over to the controller...
try {
    v6_debug::log('launching controller');
    $registry->router->LaunchController();
}
catch (Exception $e) {
    echo '<pre>'. var_export($e,true). '</pre><br/><br/>';
    v6_debug::log('Caught exception trying to launch controller: ', $e->getMessage());
    echo 'Caught exception trying to launch controller: ', $e->getMessage(), "\n";
}


v6_debug::log('Shutting down');
v6_debug::dump();

// 12/26/2014 - execution stops here.


function __autoload($class_name)
{
    // echo '__autoload: '. $class_name. '</br>';

    $filename = strtolower($class_name) . '.class.php';

    // part of the framework?
    $file = __IPINGA_CODE_PATH . DS . $filename;
    if (file_exists($file) == true) {
        include_once($file);
        return true;
    }

    // part of the application controllers?
    $file = __WEBSITE_ROOT_PATH . DS . 'controller' . DS . $filename;
    if (file_exists($file) == true) {
        include_once($file);
        return true;
    }

    // part of the application models?
    $file = __WEBSITE_ROOT_PATH . DS . 'model' . DS . $filename;
    if (file_exists($file) == true) {
        include_once ($file);
        return true;
    }

    // some other class?
    $file = __WEBSITE_ROOT_PATH . DS . 'classes' . DS . $filename;
    if (file_exists($file) == true) {
        include_once ($file);
        return true;
    }

    // is this a helper class?
    $filename = strtolower($class_name) . '.helper.php';
    $file     = __IPINGA_CODE_PATH . DS . $filename;
    if (file_exists($file) == true) {
        include_once ($file);
        return true;
    }

    return false;
}


function handleShutdown()
{
    v6_debug::dump();

    // 12/28/2014 - set the cookie.  May not want this in production if there is an error, etc.  You may need to move
    // this into the if clause below
    v6_cookie::set();

    @ob_end_flush();

    $error = error_get_last();
    if (($error !== NULL) && ($error['type'] == 1)) {
//        ob_end_clean();   // silently discard the output buffer contents.
//        appSendMsgToVern('Error has occurred',$error);
//        header( 'location:/fatal_error' );
        @ob_end_flush(); // output what is stored in the internal buffer  (may not want this here in production)
        echo '<pre>' . var_export($error, true);
        v6_BackTrace();
        die('handleShutdown(): Cannot continue!');
    } else {
        @ob_end_flush(); // output what is stored in the internal buffer
    }
}







?>