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


/**
 * Class v6_debug
 */
class v6_debug
{

    /**
     * @var array $calls
     * @default null
     *
     * 12/26/2014 - Will contain the list of calls in the traceback with some other stuff added in to make it pretty
     * This could be NULL if debug::log() has never been called yet, because you can't initialize a STATIC variable
     * as an array.
     */
    private static $calls;

    /**
     * @var bool $debug
     * @default false
     *
     * 12/26/2014 - Set $debug to false to ignore any calls to the debug methods in your code.  This allows you to leave
     * the calls in place in your code and simply switch debugging off
     */
    public static $debug = false;

    /**
     * @var bool $immediate
     * @default false
     *
     * 12/26/2014 - Immediately log to the text file?  if false, you will need to call v6_debug::dump() to write to the
     * text file
     */
    public static $immediate = false;


    /**
     * @var string $textfile
     * @default 'debug.log.php'
     *
     * 12/26/2014 - In previous versions, this was enroneously hardcoded as 'debug.log.php'   Now it can be changed
     */
    public static $textfile = 'debug.log.php';


    /**
     * @param string $message
     *
     * 12/26/2014 - The string you want written to the debug text file
     */
    public static function log($message = '')
    {
        if (self::$debug == true) { // are we in debug mode or ignore mode?

            // make sure it's an array, since we can't initialize a static as an array
            if (!is_array(self::$calls)) {
                self::$calls = array();
            }

            $c = debug_backtrace(false);
            //  $c = (isset($c[1]))?$c[1]:$c[0];
            $c = $c[0];

            $call              = array();
            $call['file']      = $c['file'];
            $call['line']      = $c['line'];

            // $call['message']   = $message;
            $call['message']   = $message;

            $call['Date/Time'] = date('Y-m-d H:i:s');
            self::$calls[]     = $call;

            if (self::$immediate == true) {
                self::dump(self::$textfile);
            }

        }

    }


    /**
     * @param string $filename
     * @default ''
     *
     * 12/26/2014 - the name of the textfile you want to log to
     *
     */
    public static function dump($filename = '')
    {

        // error_log(self::$textfile);
        // error_log(v6_debug::$textfile);

        if (self::$debug == true) {

            // 12/26/2014 - added defaulting to debug::textfile
            if ( empty($filename)==true ) {
                $filename = self::$textfile;
            }

            if (strlen($filename) > 0) {
                $fh = fopen($filename, 'a') or die("debug::dump() - can't open file ". $filename);
                fseek($fh, 0, SEEK_END);
                foreach (self::$calls as $c) {
                    fwrite($fh, $c['message'] . '  ' . $c['Date/Time'] . '  ' . substr('   (' . $c['line'], -5) . ') ' . $c['file']);
                    fwrite($fh, "\r\n");
                }
                fclose($fh);
            } else {
                // 12/26/2014 - If no filename is specified then simply echo the debug info to the screen.
                // THIS COULD BE DANGEROUS!
                echo '<pre>';
                echo var_export(self::$calls, true);
                echo '</pre>';
            }

            // 12//26/2014 - If in immediate mode, then clear the call stack
            if (self::$immediate == true) {
                self::$calls = array();
            }

        }
    }
}

?>