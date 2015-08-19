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


Class signupController Extends project_Controller
{

    // http://yoursite.com/signup
    public function index()
    {
        // if they click on signup, make sure they aren't logged in
        // this is bit of a kludge in that since we are logging out, we don't want to make the template
        // ready, until AFTER the logout has occurred otherwise, menu options will still be created and displayed
        // as though the user is still logged in.... because technically he would be at that point.
        $mgr = new v6_manager();
        $mgr->logout();

        // now we can do everything we normally do...
        appMakeTemplateReady($this->template);

        // this test is so the url doesn't get funky...
        //  ie: if you come here as a get, then the form is shown and if you come here as a post, I try to authenticate you
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_process();
        } else {
            $this->template->mgr->logout(); // if they are logging in, then make sure they are logged out as of now
            $this->template->show('signup.form');
        }

    }

    public function _process()
    {

        appMakeTemplateReady($this->template);

        $v = new v6_validator($_POST, $this->template);

        $v->check_string('first_name','First Name',2,25,true);
        $v->check_string('last_name','Last Name',2,25,true);
        $v->check_email('email', 'E-Mail Address', true);
        $v->check_password('passwd', 'Password', 4, 20, true, false);

        if (empty($v->message) == false) {
            $this->template->message = 'Please fix input errors.';
            $this->template->show('signup.form');
        } else {

            if ($this->template->user->is_dupe_email($_POST['email']) == true) {
                $this->template->message = 'Please correct input errors below.';
                $this->template->email_hint = 'Account already exists with this email address.';
                $this->template->show('signup.form');
            } else {
                $this->template->user->first_name       = $_POST['first_name'];
                $this->template->user->last_name        = $_POST['last_name'];
                $this->template->user->email            = strtolower($_POST['email']);
                $this->template->user->passwd           = $_POST['passwd'];
                $this->template->user->save();

                $this->template->message = 'Account created successfully.  You may now login';
                $this->template->show('login.form');
            }

        }

    }   // process login






}
