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


Class v6_cargo
{
    public $vars = array();

    function __construct()
    {
        // any startup code should go here
    }

    /**
     * set variables
     *
     * @param string $index
     * @param mixed $value
     * @returns void
     */
    public function __set($index, $value)
    {
        $this->vars[$index] = $value;
    }

    /**
     * get variables
     *
     * @param mixed $index
     * @return mixed
     */
    public function __get($index)
    {
        return $this->vars[$index];
    }


    // 12-26-2014 - new
    public function clear($index)
    {
        if (isset($this->vars[$index])==true) {
            unset($this->vars[$index]);
        }
    }

    // 12-26-2014 - new
    function as_json()
    {
        return json_encode($this->vars);
    }


    /**
     * A simple container class for variables, etc.
     * @return array List of variables in this cargo instance
     */
    public function varnames()
    {
        $r = array();
        foreach ($this->vars as $k => $v) {
            $r[] = $k;
        }
        return $r;
    }

}