<?php
/**
 * Created by Vern
 * Date: 12/19/11
 * Time: 1:20 PM
 */
defined('__VERN') or die('Restricted access');
?>
<!-- start of top_banner.html.standard.php -->
<div id="layout-top">

    <div class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" style="padding: .5em;">

        <div style="float: left;">
            <img src="/images/logo.png">
        </div>

        <div style="float:right">

            <?php if ($this->show_login_form == true) {

                if ($this->mgr->UserIsLoggedIn(false) == false) {

                    ?>


                    <form method="post" action="/login">
                        <table cellpadding="0" cellspacing="2px">
                            <tr>
                                <td style="text-align: right; border-bottom: 3px;">
                                    <label
                                        style="display:inline;"
                                        for="email">E-Mail&nbsp;Address
                                    </label>
                                    <input
                                        type="text"
                                        name="email"
                                        width="200px"
                                        class="text ui-widget-content ui-corner-all">
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right">
                                    <label
                                        style="display:inline;"
                                        for="passwd">Password
                                    </label>
                                    <input
                                        type="password"
                                        name="passwd"
                                        class="text ui-widget-content ui-corner-all">
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2 align="right">
                                    <a style="font-size: 8px;" href="/forgot_password">Forgot Password?</a>
                                    <a class="button" href="/signup">Create Account</a>
                                    <button class="button" name="btn_login">Login</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <div style="clear:right;"></div>

                <?php } else {

                    echo $this->user->first_name . ' ' . $this->user->last_name;
                    ?>

                    <br/>
                    <div style="font-size: 8px"><a href="/logout"> Logout</a></div>
                    <br/>

                    <div style="clear:right;"></div>

                <?php } ?>

            <?php } ?>

        </div>
        <div style="clear: both;"></div>
    </div>
</div>
<!-- end of top_banner.html.standard.php -->
