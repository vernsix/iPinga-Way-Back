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
    $this->show_login_form = false;
    $this->standard('top_banner.html');
    $this->standard('left_menu.html');
    ?>

    <div id="layout-content">

        <div class="ui-accordion ui-widget ui-helper-reset">
            <h3 class="ui-accordion-header ui-state-default ui-accordion-header-active ui-state-active ui-corner-top" style="border-bottom: none;">Please Login</h3>
        </div>

        <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active">

            <div style="margin: 50px auto; text-align: center">

                <form id="frm" action="/login" method="post">

                    <div style="width:258px; margin:0 auto;">
                        <?php $this->standard('error_panel.html'); ?>
                    </div>

                    <?php
                    $this->form_field(array(
                        'table' => $user,
                        'field_name' => 'email',
                        'label' => 'E-Mail Address',
                        'checkpostvars' => true,
                        'showhints' => true
                    ));
                    $this->form_field(array(
                        'table' => $user,
                        'field_name' => 'passwd',
                        'label' => 'Password',
                        'type' => 'password',
                        'checkpostvars' => true,
                        'showhints' => true
                    ));
                    ?>

                    <br/>
                    <a href="/forgot_password" style="font-size: 10px;">Forgot Password? Click Here</a><br/>
                    <br/><br/>

                    <button class="button">Login</button>

                </form>

            </div>

        </div>

    </div>

    <?php $this->standard('bottom_banner.html'); ?>

</div>
<!-- container -->

</body>
</html>



