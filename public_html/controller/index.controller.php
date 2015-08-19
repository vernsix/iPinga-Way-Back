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


Class indexController Extends project_Controller
{

    // http://yoursite.com
    public function index()
    {
        appMakeTemplateReady($this->template);
        $this->template->show('index');
    }

    // http://yoursite.com/index/show_user
    // showing how SendJSON() works
    public function show_user()
    {
        $u = new v6_user_table('users');
        $u->load_by_id(8);
        $this->json['user_one'] = $u;
        $this->SendJSON();
    }

    // http://yoursite.com/index/add_user
    // quickly add a user to your application
    public function add_user()
    {
        $u = new v6_user_table('users');
        $u->id = 0;     // adding
        $u->email = 'you@example.com';
        $u->passwd = 'password';
        $u->first_name = 'Jim';
        $u->last_name = 'Smith';
        $u->save();

        $this->json['new_user'] = $u;
        $this->SendJSON();
    }

}
?>