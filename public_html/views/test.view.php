<html>





<head>

    <meta charset="utf-8">
    <title>My test screen</title>
    <?php $this->framework('latest_jquery_and_jqueryui'); ?>
    <link rel="stylesheet" href="/css/style.css">

</head>


<body>

<div id="layout-container">

    <!-- div id="layout-content" -->

        <div class="ui-widget ui-widget-content ui-corner-all margin_bot10" style="min-height: 200px; width: 350px; margin: 0 auto;">

            <div class="ui-accordion-header ui-helper-reset ui-state-default ui-state-active ui-corner-top"
                 style="padding: 10px;">
                <span style="margin-top: 10px; margin-bottom: 10px;">Please Login</span>
            </div>

            <div style="margin: 50px auto; text-align: center">

                <form id="frm" action="/login" method="post">

                    <?php
                    $this->form_field(array(
                        'table'     => $user,
                        'field_name'=> 'email',
                        'label'     => 'E-Mail Address',
                        'checkpostvars' => true,
                        'showhints' => true
                    ));
                    $this->form_field(array(
                        'table'     => $user,
                        'field_name'=> 'passwd',
                        'label'     => 'Password',
                        'type'      => 'password',
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

    <!-- /div -->


</div>  <!-- container -->










</body>
</html>