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

// Here is where you should add any application specific code that you want to write.  You can obviously do
// any includes if you prefer to keep the code separated


/**
 * This little function is just something I use to set up the template.  I use it everywhere, so it should be its own
 *
 * @param v6_template $template
 * @param v6_manager  $mgr
 */
function appMakeTemplateReady($template)
{
    $template->mgr = new v6_manager();
    $loggedIn = $template->mgr->UserIsLoggedIn(false);

    $template->user = new v6_user_table('users');
    // if the user is logged in, then load his user record and make it available to the template
    if ($loggedIn == true) {
        $template->user->load_by_id($template->mgr->logged_in_details['USER_ID']);
    }

    $template->skin = 'sunny'; // you could set this as an option in the user table if you like

    // now build the menu for the left rail...
    $m = new v6_menu();
    $m->add_item('Main Menu', 'Home', '/');

    if ($loggedIn == true) {
        $m->add_item('Main Menu', 'Account Settings', '/account_settings');
        $m->add_item('Main Menu', 'Logout', '/logout');
    } else {
        $m->add_item('Main Menu', 'Login', '/login');
        $m->add_item('Main Menu', 'Sign Up', '/signup');
    }


    if ($loggedIn == true) {

        // only show this option if the user is authorized via our simple acl object
        $template->acl = new v6_acl('users', 'email');

        if( $template->acl->has_access('testing',$template->user->id) == true ) {
            $m->add_item('Test Menu', 'This is a test', '/test');
        }

    }

    $template->menu_html = $m->as_html();

}


?>