<?php
/*
    Vern Six MVC Framework version 3.0

    Copyright (c) 2007-2015 by Vernon E. Six, Jr.
    Author's websites: http://www.iPinga.com and http://www.VernSix.com

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to use
    the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice, author's websites and this permission notice
    shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
    IN THE SOFTWARE.
*/
defined('__VERN') or die('Restricted access');

class v6_security
{

    /*
     * The heart and soul of the security system.
     *
     * How the security system works...
     *
     * There are "priviledges" and there are "permissions".
     *
     * You have to have been granted permission to exercise some privilege.  ie: editing a particular table
     * is a privilege and you have to be granted permission to exercise that privilege.
     *
     * All privileges are kept in the privileges table.  Whether or not the user has permission is kept in the
     * "user_permissions" table.
     *
     * Users can also be a member of one or more groups.    Groups also have permissions
     *
     * @todo test for super user to override security system
     */
    public function IsAllowed($user_id, $privilege_id_descrip)
    {

        $privilege_id = $this->PrivilegeId($privilege_id_descrip);

        $result = false;

        if ($this->get_row_count(sprintf("select count(*) as num_rows from user_permissions where user_id=%d and privilege_id=%d and allow_deny='D'", $user_id, $privilege_id)) > 0) {

            $result = false; // user has been specifically denied permission to this privilege

        } elseif($this->get_row_count(sprintf("select count(*) as num_rows from user_permissions where user_id=%d and privilege_id=%d and allow_deny='A'", $user_id, $privilege_id)) > 0) {

            $result = true; // user has been specifically allowed permission to this privilege

        } elseif ($this->get_row_count(sprintf("select count(*) as num_rows from group_permissions where privilege_id=%d and allow_deny = 'A' and group_id in (select group_id from group_members where user_id=%d)", $privilege_id, $user_id)) > 0) {

            $result = true; // a group this user is a member of has been allowed permission to this privilege

        } elseif ($this->get_row_count(sprintf("select count(*) as num_rows from group_permissions where privilege_id=%d and allow_deny = 'D' and group_id in (select group_id from group_members where user_id=%d)", $privilege_id, $user_id)) > 0) {

            $result = false; // a group this user is a member of has been denied permission to this privilege

        }

        return $result;
    }


    /*
     * Quickly execute a piece of SQL to get the number of rows in a result set.
     * @todo tighten up error catch
     */
    private function get_row_count($sql)
    {
        global $registry;
        try {
            $stmt = $registry->db->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $result = $row['num_rows'];
        }
        catch (PDOException $e) {
            echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
            $result = 0;
        }
        return $result;
    }


    /*
     * get the privilege_id, either supplied by user or looked up by description
     */
    public function PrivilegeId($privilege_id_descrip)
    {
        if (is_string($privilege_id_descrip) == true) {
            $privilege = new v6_table('privileges');
            $privilege->load_by_secondary_key('descrip', $privilege_id_descrip);
            $result = $privilege->id;
        } else {    // otherwise it's really an int not a string so return the int without looking it up from a string
            $result = $privilege_id_descrip;
        }
        return $result;
    }


    /*
     * sets the user's permission to either allow or deny.  To clear the privilege... set $allow_deny to an empty string
     */
    public function SetUserPermission($user_id, $privilege_id_descrip, $allow_deny)
    {
        $privilege_id = $this->PrivilegeId($privilege_id_descrip);
        $user_permission = new v6_table('user_permissions');
        $user_permission->load_by_custom_where(sprintf('user_id=%d and privilege_id=%d', $user_id, $privilege_id));
        if (strlen(trim($allow_deny)) == 0) {
            $id = $user_permission->id;
            if ($id <> 0) { // only delete it if we found it
                $user_permission->delete_by_id($id);
            }
        } else {
            $user_permission->user_id = $user_id;
            $user_permission->privilege_id = $privilege_id;
            $user_permission->allow_deny = $allow_deny;
            $user_permission->save();
        }
    }


    /*
     * sets the group's permission to either allow or deny.  To clear the privilege... set $allow_deny to an empty string
     */
    public function SetGroupPermission($group_id, $privilege_id_descrip, $allow_deny)
    {
        $privilege_id = $this->PrivilegeId($privilege_id_descrip);
        $group_permission = new v6_table('group_permissions');
        $group_permission->load_by_custom_where(sprintf('group_id=%d and privilege_id=%d', $group_id, $privilege_id));
        if (strlen(trim($allow_deny)) == 0) {
            $id = $group_permission->field['id'];
            if ($id <> 0) { // only delete it if we found it
                $group_permission->delete_by_id($id);
            }
        } else {
            $group_permission->group_id = $group_id;
            $group_permission->privilege_id = $privilege_id;
            $group_permission->allow_deny = $allow_deny;
            $group_permission->save();
        }
    }


}

?>