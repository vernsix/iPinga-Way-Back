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

class v6_log
{
    public $breeds;
    public $filename;
    public $fh;
    public $instance_name;

    function __construct($filename = 'logfile.php', $instance_name = null)
    {
        // I gave it a .PHP extension so it couldn't be downloaded

        // this should be in a parameter table somewhere, but for now just turn them all on or off here
        $this->breeds = array();
        $this->breeds['xml'] = true;
        $this->breeds['misc'] = true;
        $this->breeds['info'] = true;
        $this->breeds['sql'] = true;
        $this->breeds['PDOException'] = true;

        $this->filename = $filename;

        if (isset($instance_name) == true) {
            $this->instance_name = $instance_name;
        } else {
            $this->instance_name = (string)time(); // change this to whatever you want
        }


    }

    function __destruct()
    {
        if (isset($this->fh)) {
            fclose($this->fh);
        }
    }

    function CheckFileHandle()
    {
        if (!isset($this->fh)) {
            $this->fh = fopen($this->filename, 'a') or die("can't open file ". $this->filename);
        }
        fseek($this->fh, 0, SEEK_END);
    }

    /**
     * My preferred logging function.  Logs to a file called logfile.php in the document root directory.
     * @param string $breed what type of message/activity are we logging (this can be anything you like so long as you edit this function to allow for it)
     * @param string @msg the text message you wish to log if the breed is being logged
     */
    function text($breed, $text)
    {
        if ((isset($this->breeds[$breed]) == true) && ($this->breeds[$breed] == true)) {
            fwrite($this->fh, date("Y-m-d H:i:s") . " [ " . $breed . " ] [" . $this->instance_name . "] " . $text . "\r\n");
        }
    }

    function dump($breed, $var)
    {
        if ((isset($this->breeds[$breed]) == true) && ($this->breeds[$breed] == true)) {
            fwrite($this->fh, date("Y-m-d H:i:s") . " [ " . $breed . " ] [" . $this->instance_name . " ] ================================" . "\r\n");
            fwrite($this->fh, var_export($var, true) . "\r\n\r\n");
        }
    }

}
?>