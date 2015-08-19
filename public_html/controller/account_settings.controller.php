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


Class account_settingsController Extends project_Controller
{

    // http://yoursite.com/test
    // this url should only be visible to those users with the correct ACL database table entries
    public function index()
    {
        appMakeTemplateReady($this->template);

        // we have to make sure this url can be seen by this user!
        // he must be logged in and he must have the correct ACL table entries
        if ($this->template->mgr->UserIsLoggedIn(false) == false) {
            $this->template->message = 'You must be logged in to view this page';
            $this->template->show('login.form');    // this shouldn't return
        } else {

                // I know it is the error template, but all I am doing is presenting the user with a message
                $this->template->title = 'Account Settings';
                $this->template->message = 'This is where the user could edit their password, etc if this were a real app';
                $this->template->show('error');

        }
    }

}
