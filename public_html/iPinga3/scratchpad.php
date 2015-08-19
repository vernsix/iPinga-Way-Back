<?php

?>

<html>

<head>

    <meta charset="utf-8">
    <title>My test screen</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/start/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="/css/style.css">

</head>


<body>

<div id="layout-container">

    <!-- div id="layout-content" -->

    <div class="ui-widget ui-widget-content ui-corner-all margin_bot10"
         style="min-height: 200px; width: 350px; margin: 0 auto;">

        <div class="ui-accordion-header ui-helper-reset ui-state-default ui-state-active ui-corner-top"
             style="padding: 10px;">
            <span style="margin-top: 10px; margin-bottom: 10px;">Please Login</span>
        </div>

        <div style="margin: 50px auto; text-align: center">

            <form id="frm" action="/login" method="post">

                <label for="email" class="text">E-Mail Address</label>
                <input type="text" name="email"
                       class="text ui-widget-content ui-corner-all"
                       value="">
                <label for="passwd" class="text">Password</label>
                <input type="password" name="passwd" class="text ui-widget-content ui-corner-all" value="">



                <br/>
                <a href="/forgot_password" style="font-size: 10px;">Forgot Password? Click Here</a><br/>
                <br/><br/>

                <button class="button">Login</button>

            </form>

        </div>

    </div>

    <!-- /div -->


</div>
<!-- container -->


</body>
</html>




