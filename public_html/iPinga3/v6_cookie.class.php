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

class v6_cookie
{

    public static $contents;

    function __construct()
    {
        self::initialize();
    }

    public static function initialize()
    {
        global $vern_config;
        if (isset(self::$contents) == false) {

            // start with nothing
            self::$contents = array();

            // if the request came in with a cookie, decrypt it into our contents
            if (isset($_COOKIE[$vern_config['cookie']['name']]) == true) {
                $crypto = new v6_crypto();
                $dec_json = $crypto->decrypt($_COOKIE[$vern_config['cookie']['name']]);
                $a = json_decode($dec_json, true);
                self::$contents = $a['kludge'];
            }

        }
    }

    // add is really add and replace
    public static function add($key, $value)
    {
        self::initialize();
        self::$contents[$key] = $value;
    }

    public static function drop($key)
    {
        self::initialize();
        if (isset(self::$contents[$key]) == true) {
            unset(self::$contents[$key]);
        }
    }

    public static function keyexists($key)
    {
        self::initialize();
        $retval = false;
        if (isset(self::$contents[$key]) == true) {
            $retval = true;
        }
        return $retval;
    }

    public static function keyvalue($key)
    {
        self::initialize();
        $retval = NULL;
        if (isset(self::$contents[$key]) == true) {
            $retval = self::$contents[$key];
        }
        return $retval;
    }


    // this should only be called once per program execution.  Currently I call it in the HandleShutdown() function in start_vern.php
    public static function set()
    {
        global $vern_config;

        self::initialize();

        if (count(self::$contents) == 0) {
            if (isset($_COOKIE[$vern_config['cookie']['name']]) == true) {
                setcookie($vern_config['cookie']['name'], '', 1, '/');   // expire right now
            }   // no need for an else branch, as it wasn't there to begin with
        } else {
            $a = array('kludge' => self::$contents);
            $crypto = new v6_crypto();
            $enc_json = $crypto->encrypt(json_encode($a));
            setcookie($vern_config['cookie']['name'], $enc_json, $vern_config['cookie']['expiration_time'],'/');
        }

    }

    public static function clear()
    {
        self::$contents = array();
    }


    // put all the contents in the header for debugging
    public static function debug($suffix = '')
    {
        foreach (self::$contents as $k => $v) {
            header('X-CryptoCookie-' . $suffix . $k . ': ' . json_encode($v));
        }
    }

    // easy to see what was in a cookie this way
    public static function decrypt($enc_string)
    {
        $crypto = new v6_crypto();
        $dec_json = $crypto->decrypt($enc_string);
        $a = json_decode($dec_json, true);
        return $a;
    }



}

?>