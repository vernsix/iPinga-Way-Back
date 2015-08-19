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


Class loginController Extends project_Controller
{

    // http://yoursite.com/login
    public function index()
    {
        appMakeTemplateReady($this->template);

        // this test is so the url doesn't get funky...
        //  ie: if you come here as a get, then the form is shown and if you come here as a post, I try to authenticate you
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_process();
        } else {
            $this->template->mgr->logout(); // if they are logging in, then make sure they are logged out as of now
            $this->template->show('login.form');
        }
    }

    public function _process()
    {

        appMakeTemplateReady($this->template);

        $v = new v6_validator($_POST, $this->template);

        $v->check_email('email', 'E-Mail Address', true);
        $v->check_password('passwd', 'Password', 4, 20, true, false);

        if (empty($v->message) == false) {
            $this->template->message = 'Please fix input errors.';
            $this->template->show('login.form');
        } else {

            $acl = new v6_acl('users','email','passwd');

            if ($acl->authenticate($_POST['email'],$_POST['passwd']) == true)   {

                $this->json['mgr_before'] = clone $this->template->mgr;

                // user provided good credentials
                $this->template->mgr->update( $acl->user_table->id );

                header( 'location: /' );

            } else {

                // user blew it
                $this->template->message = 'Login Failed';
                $this->template->show('login.form');

            }

        }

    }   // process login






}
