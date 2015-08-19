<?php
/**
 * @author Vern Six <Vern@VernSix.com>
 * @copyright Copyright (c) 2007-2011, Vernon E. Six, Jr. - All Rights Reserved
 * @package app_view
 * @version 1.0
 * @since 1.0
 */
defined('__VERN') or die('Restricted access');
$this->standard('head.html');
?>
<body>

<div id="layout-container">

    <?php
    $this->show_login_form = true;
    $this->standard('top_banner.html');
    $this->standard('left_menu.html');
    ?>

    <div id="layout-content">

        <div class="ui-widget ui-widget-content ui-corner-all margin_bot10" style="min-height: 400px; padding: 10px;">

            <div
                style="color: #1977be; font-size:22px; padding:6px; font-weight: bolder; line-height: 1.2; margin-bottom: 10px;">
                <div align="left" style="font-size:30px;">Welcome to iPinga...</div>
                <div align="right">This is just a simple web application to demo the framework!</div>
            </div>
            <div style="clear: both;"></div>


            <!-- blue panel -->
            <div class="ui-helper-reset ui-corner-all"
                 style="background-color: #1977be; padding:10px; width: 278px; float:left;">

                <p style="color:#ffffff; font-size: 14px; font-weight: bolder; margin-top: 0px;">What is iPinga?</p>

                <p style="color:#ffffff;">
                    iPinga is a powerful PHP framework with a extremely small footprint, committed to simplicity and
                    productivity.<br/><br/>

                    iPinga is based on the belief that programming should be enjoyable as well as productive. iPinga has
                    been built for
                    PHP developers who need a simple and elegant framework to quickly create full-featured web
                    applications. While
                    some other frameworks try to be all things to all people, iPinga excels because of its simplicity
                    and ease of
                    rapid development.<br/><br/>

                    If you're a PHP developer who lives in the real world of shared hosting accounts and clients with
                    deadlines, and if you're tired of needlessly bloated and undocumented frameworks, then iPinga is
                    probably just what you have been looking for.<br/><br/>

                </p>
            </div>


            <!-- right panel -->
            <div class="ui-widget ui-widget-content ui-corner-all margin_bot10" style="width: 402px; float: right;">

                <div style="padding: 10px;">

                    <p>
                        <b>DEMO APPLICATION</b><br/><br/>
                        This is a simple demo application designed to show you how to work and develop within the iPinga
                        framework.
                    </p>

                    <p>
                        This demo application doesn't do much except allow a user to create an account and login.
                        I am assuming you can take it from there.
                    </p>

                    <p>
                        There is a simple ACL demo in the menu structure to the left as well. Look at the
                        index.controller.php source code for more info.
                    </p>

                    <div style="width: 125px; margin: 0 auto;">
                        <a class="button" href="/signup">Sign Up Today!</a>
                    </div>

                </div>

                <div style="clear: both;"></div>

            </div>

            <div style="clear:both;"></div>


        </div>

    </div>

    <?php $this->standard('bottom_banner.html'); ?>

</div>
<!-- container -->

</body>
</html>



