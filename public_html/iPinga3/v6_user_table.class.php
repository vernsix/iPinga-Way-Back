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

/**
 * @property int    $id
 * @property string $first_name
 * @property string $last_name
 * @property string $passwd
 * @property string $email
 * @property string $droppage
 * @property string $skin
 * @property int    $advertiser_id
 */
Class v6_user_table Extends v6_table
{

    public function load_by_email($email = '')
    {
        $this->clear();
        global $registry;
        try {
            $sql = 'select * from ' . $this->table_name . ' where email = :email';
            $this->last_sql = $sql;
            $stmt = $registry->db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $this->_process_loadby_execute($stmt);
        } catch (PDOException $e) {
            echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
            $this->saved = false;
        }
        return $this->saved;
    }

    public function is_dupe_email($email = '')
    {
        global $registry;
        $IsDupe = true;
        if (!empty($email)) {

            try {
                $sql = 'select count(*) as row_count from ' . $this->table_name . ' where email = :email';
                $this->last_sql = $sql;
                $stmt = $registry->db->prepare($sql);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row['row_count'] == 0) {
                    $IsDupe = false;
                }
            } catch (PDOException $e) {
                echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
                $this->saved = false;
            }
        }
        return $IsDupe;
    }


    public function load_by_username($username = '')
    {
        $this->clear();
        global $registry;
        try {
            $sql = 'select * from ' . $this->table_name . ' where username = :username';
            $this->last_sql = $sql;
            $stmt = $registry->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $this->_process_loadby_execute($stmt);
        } catch (PDOException $e) {
            echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
            $this->saved = false;
        }
        return $this->saved;
    }

    public function is_dupe_username($username = '')
    {
        global $registry;
        $IsDupe = true;
        if (!empty($email)) {

            try {
                $sql = 'select count(*) as row_count from ' . $this->table_name . ' where username = :username';
                $this->last_sql = $sql;
                $stmt = $registry->db->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row['row_count'] == 0) {
                    $IsDupe = false;
                }
            } catch (PDOException $e) {
                echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
                $this->saved = false;
            }
        }
        return $IsDupe;

    }





}

?>