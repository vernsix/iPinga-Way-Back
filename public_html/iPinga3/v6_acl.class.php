<?php
/**
 * Written by: vern
 * Date: 8/5/2015
 * Time: 9:02 PM
 * CopyRight (c) 2015 - All Rights Reserved Worldwide
 */
defined('__VERN') or die('Restricted access');


/*
 * This is a ridiculously simple ACL system.  The v6_security class is much more robust, but
 * sometimes... simple is just better and perfectly fine for most tasks.
 */

class v6_acl
{

    public $user_table;

    public $user_tablename;
    public $username_fieldname;
    public $password_fieldname;
    public $email_fieldname;
    public $privil_tablename;


    /**
     * @param v6_table $user_table_override
     */
    public function __construct($user_tablename = 'user', $username_fieldname = 'username', $password_fieldname =
    'passwd', $email_fieldname = 'email', $privil_tablename = 'acl')
    {
        $this->user_tablename = $user_tablename;
        $this->user_table = new v6_table($user_tablename);
        $this->username_fieldname = $username_fieldname;
        $this->password_fieldname = $password_fieldname;
        $this->email_fieldname = $email_fieldname;
        $this->privil_tablename = $privil_tablename;
    }   // __construct

    public function authenticate($username, $password)
    {
        $this->user_table->load_by_secondary_key($this->username_fieldname, $username);
        if ($this->user_table->saved == true) {
            if ($this->user_table->field[$this->password_fieldname] == $password) {
                $auth = true;
            } else {
                $auth = false;
            }
        } else {
            $auth = false;
        }
        return $auth;
    }

    public function has_access($accessword,$user_id=0)
    {
        if ($user_id==0) {
            $user_id = $this->user_table->id;
        }
        global $registry;
        try {
            $sql = 'select count(*) as rowcount from ' . $this->privil_tablename .
                ' where user_id = :user_id and accessword = :accessword';
            $this->last_sql = $sql;
            $stmt = $registry->db->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':accessword', $accessword);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $retval = $row['rowcount'] > 0;
        } catch (PDOException $e) {
            echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
            $retval = false;
        }
        return $retval;
    }



}