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


class v6_menu
{
    /** @var array() */
    private $links;

    public $id;


    public function __construct($id = 'accordion')
    {
        $this->links = array();
        $this->id = $id;
    }

    public function add_item( $tab, $name, $url, $target='' )
    {
        if (isset($this->links[$tab])==false) {
            $this->links[$tab] = array();
        }
        $this->links[$tab][] = new v6_menu_item($name,$url,$target);
    }

    public function as_html()
    {
        $r = '<!-- Start of accordion menu -->'. "\r\n";
        $r .= '<ul id="'. $this->id . '">' . "\r\n";
        foreach ($this->links as $tab => $menu_items) {
            $r .= '<li>'. "\r\n";
          	$r .= '   <h3><a>' . $tab . '</a></h3>'. "\r\n";
            $r .= '   <ul>'. "\r\n";
            foreach( $menu_items as $menu_item )  {
             	$r .= '         <li><a href="'. $menu_item->url. '"';
                if (!empty($menu_item->target))
                {
                    $r .= ' target="'. $menu_item->target . '"';
                }
                $r .= '>' . $menu_item->name . '</a></li>'. "\r\n";
            }
            $r .= '   </ul>'. "\r\n";
            $r .= '</li>'. "\r\n";
        }
        $r .= '</ul>';
        $r .= '<!-- End of accordion menu -->'. "\r\n";
        return $r;
    }

}

class v6_menu_item
{
    public $name;
    public $url;
    public $target;

    public function __construct($name,$url,$target='')
    {
        $this->name = $name;
        $this->url  = $url;
        $this->target = $target;
    }
}


?>