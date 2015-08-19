<?php
/**
 * Created by Vern
 * Date: 12/19/11
 * Time: 6:31 PM
 */
defined('__VERN') or die('Restricted access');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>

    <title>iPinga.com - Home</title>

    <?php $this->framework('latest_jquery_and_jqueryui'); ?>

    <link type="text/css" rel="stylesheet" href="/css/style.css"/>

    <script type="text/javascript">
        $(document).ready(function () {

            var dialogTermsOfUse;
            dialogTermsOfUse = $("#dialog-terms-of-use").dialog({
                autoOpen: false,
                height: 300,
                width: 350,
                modal: true,
                buttons: {
                    "Close": function () {
                        dialogTermsOfUse.dialog("close");
                    }
                }
            });
            $("#link-terms-of-use").on("click", function () {
                dialogTermsOfUse.dialog("open");
                return false;
            });

            var dialogPrivacyNotice;
            dialogPrivacyNotice = $("#dialog-privacy-notice").dialog({
                autoOpen: false,
                height: 300,
                width: 350,
                modal: true,
                buttons: {
                    "Close": function () {
                        dialogPrivacyNotice.dialog("close");
                    }
                }
            });
            $("#link-privacy-notice").on("click", function () {
                dialogPrivacyNotice.dialog("open");
                return false;
            });

            var dialogLegal;
            dialogLegal = $("#dialog-legal-disclaimer").dialog({
                autoOpen: false,
                height: 300,
                width: 350,
                modal: true,
                buttons: {
                    "Close": function () {
                        dialogLegal.dialog("close");
                    }
                }
            });
            $("#link-legal-disclaimer").on("click", function () {
                dialogLegal.dialog("open");
                return false;
            });




            $(".button").button();
            <?php $this->standard('accordion.js'); ?>

            <?php
                // show an error message panel?
                if ( (isset($message)==true) && (empty($message)==false) ){
            ?>
            $("#errorMessage").empty();
            $("#errorMessage").append('<?php echo $message; ?>');
            $("#errorPanel").removeClass("hidden");
            <?php
                }
            ?>

        });
    </script>

</head>
