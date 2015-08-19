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
 * @param int $errno         contains the level of the error raised
 * @param string $errstr     contains the error message
 * @param string $errfile    optional: contains the filename that the error was raised in
 * @param int $errline       optional: contains the line number the error was raised at
 * @param array $errcontext  optional: an array that points to the active symbol table at the point the error occurred
 */
function v6_error_handler($errno, $errstr, $errfile, $errline, $errcontext){
    $errno = $errno & error_reporting();
    if($errno == 0) return;

    // in some cases, I have found these two have not been defined in some versions/builds of PHP.
    //  Oddness, so I define them if not defined already
    if(!defined('E_STRICT'))            define('E_STRICT', 2048);
    if(!defined('E_RECOVERABLE_ERROR')) define('E_RECOVERABLE_ERROR', 4096);


    print "<pre>\n<b>";
    switch($errno){
        case E_ERROR:               print "Error";                  break;
        case E_WARNING:             print "Warning";                break;
        case E_PARSE:               print "Parse Error";            break;
        case E_NOTICE:              print "Notice";                 break;
        case E_CORE_ERROR:          print "Core Error";             break;
        case E_CORE_WARNING:        print "Core Warning";           break;
        case E_COMPILE_ERROR:       print "Compile Error";          break;
        case E_COMPILE_WARNING:     print "Compile Warning";        break;
        case E_USER_ERROR:          print "User Error";             break;
        case E_USER_WARNING:        print "User Warning";           break;
        case E_USER_NOTICE:         print "User Notice";            break;
        case E_STRICT:              print "Strict Notice";          break;
        case E_RECOVERABLE_ERROR:   print "Recoverable Error";      break;
        default:                    print "Unknown error ($errno)"; break;
    }
    print ":</b> <i>$errstr</i> in <b>$errfile</b> on line <b>$errline</b>\n";

    v6_BackTrace();

    // display all variables in scope at the time the SHTF
    if (isset($errcontext)==true) {
        print var_export($errcontext,true);
    }

    print "\r\n</pre>";

    if(isset($GLOBALS['error_fatal'])){
        if($GLOBALS['error_fatal'] & $errno) {
            die('fatal '. $GLOBALS['error_fatal']. '  '.$errno );
        }
    }
}


function v6_BackTrace()
{
    if(function_exists('debug_backtrace')){
        print "\r\nbacktrace:\r\n";
        $backtrace = debug_backtrace();
        array_shift($backtrace);
        foreach($backtrace as $i=>$l){
            print "  [$i]";
            if($l['line']) print " line <b>{$l['line']}</b>";
            if($l['file']) print " in <b>{$l['file']}</b>";
            if (isset($l['class'])==true) {
                print " <b>({$l['class']}{$l['type']}{$l['function']}</b>)";
            } else {
                print " <b>({$l['function']})</b>";
            }
            print "\r\n\r\n";
        }
    }
}


/**
 * @param null $mask
 * @return int $mask
 */
function v6_error_fatal($mask = NULL)
{
    if(!is_null($mask)){
        $GLOBALS['error_fatal'] = $mask;
    }else {
        $GLOBALS['error_fatal'] = 0;
    }
    return $GLOBALS['error_fatal'];
}


/*
Usage :

error_reporting(E_ALL);      // will report all errors
set_error_handler('v6_error_handler');
v6_error_fatal(E_ALL^E_NOTICE); // will die on any error except E_NOTICE

*/

?>