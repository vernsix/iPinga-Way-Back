<?php
/**
 * The registry is an object where site wide variables can be stored without the use of a bunch of globals.
 *
 * @author Vern Six <Vern@VernSix.com>
 * @copyright Copyright (c) 2007-2011, Vernon E. Six, Jr. - All Rights Reserved
 * @package v6_mvc
 * @version 2.0
 * @since 1.0
 *
 */
defined('__VERN') or die('Restricted access');

/**
 * @property Mongo $MongoConn
 * @property MongoDB $MongoDB
 * @property PDO $db
 * @property v6_template $template
 * @property v6_router $router
 * @property v6_helper $helper
 * @property v6_log $log
 * @property v6_cookie $v6_cookie
 */
Class v6_registry extends v6_cargo
{

    public $log = array();

    function __construct()
    {
        parent::__construct();
        $this->helper = new v6_helper();
    }

}

?>