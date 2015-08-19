<?php
/**
 * Created by Vern
 * Date: 12/18/11
 * Time: 5:18 PM
 */
defined('__VERN') or die('Restricted access');
?>

<!-- start accordion.standard.php -->
var currentPanelIndex = -1;
var activePanelIndex = 0;

// make the correct panel active.  Basically replaces the jQueryUI deprecated navigation code
$("#accordion a").each( function() {
    if ( Object.prototype.toString.call(this.parentElement).slice(8, -1) == 'HTMLHeadingElement' ) {
        currentPanelIndex++;
    }
    if ( (this.href === window.location.href) && (this.href.length === window.location.href.length) ) {
        activePanelIndex = currentPanelIndex;
    }
});

$("#accordion").accordion({
    'header': 'h3',
    'autoHeight': false,
    'animation': 'easyslide',
    'active': activePanelIndex
});
<!-- end accordion.standard.php -->
