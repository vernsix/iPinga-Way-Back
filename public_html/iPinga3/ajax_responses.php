<?php
/** 
 * Defines all the AJAX return codes
 * @author Vern Six <Vern@VernSix.com>
 * @copyright Copyright (c) 2007-2013, Vernon E. Six, Jr. - All Rights Reserved
 * @version 2.0
 * @since 1.0
 */
defined('__VERN') or die('Restricted access');

define( 'AJAX_RECORD_DELETED', 		-1 );
define( 'AJAX_RECORD_SAVED', 		0 );
define( 'AJAX_NO_ERROR', 	    	0 );
define( 'AJAX_RECORD_ADDED', 		1 );
define( 'AJAX_ACCESS_DENIED', 		2 );
define( 'AJAX_INVALID_FORM_TOKEN', 	3 );
define( 'AJAX_VALIDATION_FAILURE', 	4 );
define( 'AJAX_RECORD_DATA', 		5 );
define( 'AJAX_LOGIN_FAILED',        6 );
define( 'AJAX_LOGIN_SUCCESSFUL',    7 );
define( 'AJAX_SERVICE_ERROR',       8 );
define( 'AJAX_DUPLICATE_RECORD',    9 );
define( 'AJAX_CURL_ERROR',          10 );
define( 'AJAX_NOT_LOGGED_IN',       11 );
define( 'AJAX_INVALID_ID',          12 );
define( 'AJAX_UNKNOWN_ERROR', 		1000 );

?>