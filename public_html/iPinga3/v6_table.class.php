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
 * Class v6_table
 *
 * note: You need to make sure MySql is not running in strict mode.   Try using the following SQL statement from phpmyadmin
 *  SET @@global.sql_mode= 'NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
 *
 */
class v6_table
{

    public $table_name = '';

    /** @var array|mixed */
    public $field = array();

    public $field_types = array(); // [ field_name=>field_type, ... ]
    public $cleared_values = array();
    public $cargo = array();
    public $saved = false;
    public $last_sql = '';

    function __construct($table_name)
    {
        global $registry;

        $this->table_name = $table_name;

        $sql = sprintf('describe %s', $this->table_name);
        try {
            foreach ($registry->db->query($sql) as $row) {
                $field_name = $row['Field'];

                // these are what I use.  I never define a database with anything else.
                if ($row['Type'] == 'tinyint(1)') {
                    $field_type = 'boolean';
                    $cleared_value = false;
                } elseif (substr($row['Type'], 0, 3) == 'int') {
                    $field_type = 'integer';
                    $cleared_value = 0;
                } elseif ($row['Type'] == 'timestamp') {
                    $field_type = 'timestamp';
                    $cleared_value = '';
                } elseif ($row['Type'] == 'datetime') {
                    $field_type = 'datetime';
                    $cleared_value = '';
                } elseif ($row['Type'] == 'date') {
                    $field_type = 'date';
                    $cleared_value = '';
                } elseif ($row['Type'] == 'float') {
                    $field_type = 'float';
                    $cleared_value = 0;
                } else {
                    $field_type = 'varchar';
                    $cleared_value = '';
                }
                $this->field_types[$field_name] = $field_type;
                $this->cleared_values[$field_name] = $cleared_value;
            }
        } catch (PDOException $e) {
            echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
        }
        $this->clear();
    }

    public function clear()
    {
        foreach ($this->field_types as $field_name => $field_type) {
            if ($field_name == 'passwd') {
                $this->field[$field_name] = base64_decode($this->cleared_values['passwd']);
            } else {
                $this->field[$field_name] = $this->cleared_values[$field_name];
            }
        }
        $this->cargo = array();
        $this->saved = false;
    }


    public function __set($index, $value)
    {
        $this->field[$index] = $value;
    }

    public function __get($index)
    {
        if (isset($this->field[$index])) {
            return $this->field[$index];
        }
        $c = debug_backtrace(false);
        throw new Exception('<pre>Table: ' . $this->table_name . ' Unknown Field Name: ' . $index . ' Trace: ' . var_export($c, true));
    }



    // *************************************************************************************************************
    // writing to the database...
    // *************************************************************************************************************

    public function save()
    {
        if ($this->field['id'] > 0) {
            $retval = $this->_Update();
        } else {
            $retval = $this->_Insert();
        }
        return $retval;
    }

    private function _Update()
    {
        global $registry;
        $sql = 'update ' . $this->table_name . ' set ';

        $add_comma_on_next_field = false;
        foreach ($this->field_types as $field_name => $field_type) {
            // id and timestamp take care of themselves in the database
            if (($field_name <> 'id') && ($field_type <> 'timestamp')) {
                if ($add_comma_on_next_field==true) {
                    $sql .= ', ';
                }
                $sql .= $field_name . '=:' . $field_name;
                $add_comma_on_next_field = true;
            }
        }

        $sql .= ' where id=:id';
        $stmt = $registry->db->prepare($sql);

        foreach ($this->field_types as $field_name => $field_type) {
            // id and timestamp take care of themselves in the database
            if ($field_type <> 'timestamp') {
                if ($field_name == 'passwd') {
                    $passwd = base64_encode($this->field[$field_name]);
                    $stmt->bindParam(':' . $field_name, $passwd );
                } else {
                    $stmt->bindParam(':' . $field_name, $this->field[$field_name]);
                }
            }
        }

        try {
            $retval = $stmt->execute();
            if ($this->field['id'] == 0) {
                $this->field['id'] = $registry->db->lastInsertId();
            }
            $this->saved = true;
        } catch (PDOException $e) {
            echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
            $this->saved = false;
        }
        return $retval;
    }

    private function _Insert()
    {
        global $registry;
        $sqlfields = array();
        $sqlparams = array();

        $sql = 'insert into ' . $this->table_name . ' (';
        foreach ($this->field_types as $field_name => $field_type) {
            // timestamp takes care of itself in the database
            if ($field_type <> 'timestamp') {
                $sqlfields[] = $field_name;
                $sqlparams[] = ':' . $field_name;
            }
        }
        $sql = $sql . implode(',', $sqlfields) . ') values (' . implode(',', $sqlparams) . ')';

        $sth = $registry->db->prepare($sql);
        foreach ($this->field_types as $field_name => $field_type) {
            // id and timestamp take care of themselves in the database
            if ($field_type <> 'timestamp') {
                if ($field_name == 'created') {
                    $created = date('Y-m-d H:i:s');
                    $sth->bindParam(':' . $field_name, $created);
                } elseif ($field_name == 'passwd') {
                    $passwd = base64_encode($this->field[$field_name]);
                    $sth->bindParam(':' . $field_name, $passwd);
                } else {
                    $sth->bindParam(':' . $field_name, $this->field[$field_name]);
                }
            }
        }
        try {
            $retval = $sth->execute();
            if ($this->field['id'] == 0) {
                $this->field['id'] = $registry->db->lastInsertId();
            }
            $this->saved = true;
        } catch (PDOException $e) {
            echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
            $this->saved = false;
        }
        return $retval;
    }


    // *************************************************************************************************************
    // misc delete methods...
    // *************************************************************************************************************

    /**
     * Delete the current database record that matches this object's record
     */
    public function delete()
    {
        $this->delete_by_id($this->field['id']);
    }

    /**
     * Delete a record in the database using its id
     *
     * @param integer $id
     *
     * @return bool
     */
    public function delete_by_id($id)
    {
        $this->clear();
        global $registry;
        try {
            $sql = 'delete from from '. $this->table_name .' where id = :id';
            $this->last_sql = $sql;
            $stmt = $registry->db->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
            $this->saved = false;
        }
    }



    // *************************************************************************************************************
    // misc read methods...
    // *************************************************************************************************************

    /**
     * Some other function prepares this and does all the binding.  All I am doing here is executing it in a common
     * fashion and populating all the fields() array
     *
     * @param PDOStatement $stmt
     */
    protected function _process_loadby_execute($stmt)
    {
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        foreach ($this->field_types as $field_name => $field_type) {
            if ($field_name == 'passwd') {
                $this->field[$field_name] = base64_decode($row['passwd']);
            } else {
                $this->field[$field_name] = $row[$field_name];
            }
        }

        if ($this->field['id'] < 1) {
            $this->saved = false;
        } else {
            $this->saved = true;
        }
    }

    public function load_by_id($id)
    {
        $this->clear();
        if (!$id == 0) {

            global $registry;
            try {
                $sql = 'select * from ' . $this->table_name .' where id = :id';
                $this->last_sql = $sql;
                $stmt = $registry->db->prepare($sql);
                $stmt->bindParam(':id', $id);
                $this->_process_loadby_execute($stmt);
            } catch (PDOException $e) {
                echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
                $this->saved = false;
            }

        }

        return $this->saved;
    }

    public function load_by_secondary_key($secondary_key_field_name, $key)
    {
        $this->clear();
        global $registry;
        try {
            $sql = 'select * from '. $this->table_name .' where ' . $secondary_key_field_name .' = :key';
            $this->last_sql = $sql;
            $stmt = $registry->db->prepare($sql);
            $stmt->bindParam(':key',$key);
            $this->_process_loadby_execute($stmt);
        } catch (PDOException $e) {
            echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
            $this->saved = false;
        }
        return $this->saved;
    }

    /**
     * WARNING!  This son-of-a-gun is ripe with the ability to screw the pooch!  PDO doesn't allow a dynamic where
     * clause. Meaning... you can only bindParam to field=value pairs.   It is 100% your responsibility to make
     * sure the where clause you pass to me is safe from SqlInjection.  Just remember "Bobby Tables"!!!!  YOU HAVE
     * BEEN WARNED.
     *
     * @param $where
     * @return bool
     */
    public function load_by_custom_where($where)
    {
        $this->clear();
        global $registry;
        try {
            $sql = 'select * from '. $this->table_name .' where '. $where;
            $this->last_sql = $sql;
            $stmt = $registry->db->prepare($sql);
            $this->_process_loadby_execute($stmt);
        } catch (PDOException $e) {
            echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
            $this->saved = false;
        }
        return $this->saved;
    }

    // *************************************************************************************************************
    // end of load functions
    // *************************************************************************************************************









    public function field_dump()
    {
        echo 'table_name == ' . $this->table_name . '<br><br>';
        echo 'last_sql == ' . $this->last_sql . '<br><br>';
        echo 'saved == ' . $this->saved . '<br><br>';
        echo '<table border=1 cellspacing=10 cellpadding=5><tr><td>Field Name</td><td>Type</td><td>Value</td><td>Default</td></tr>';
        foreach ($this->field as $field_name => $field_value) {
            echo '<tr>';
            echo '<td>' . $field_name . '</td>';
            echo '<td>' . $this->field_types[$field_name] . '</td>';
            echo '<td>' . $field_value . '</td>';
            echo '<td>' . $this->cleared_values[$field_name] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    public function field_as_parameter_text()
    {
        $r = '<pre>';
        foreach ($this->field as $field_name => $field_value) {
            $r .= '* @parameter ';
            if ($this->field_types[$field_name] == 'varchar') {
                $r .= 'string $' . $field_name . "\r\n";
            } else {
                if ($this->field_types[$field_name] == 'integer') {
                    $r .= 'int $' . $field_name . "\r\n";
                } else {
                    $r .= $this->field_types[$field_name] . ' $' . $field_name . "\r\n";
                }
            }
        }
        $r .= '</pre>';
        return $r;
    }

    public function field_as_loader($var_name)
    {
        $r = '<pre>';
        foreach ($this->field as $field_name => $field_value) {
            $r .= $var_name . '->' . $field_name . " = \r\n";
        }
        $r .= '</pre>';
        return $r;
    }

    public function field_as_html()
    {
        $r = '';
        $r .= '<table>';
        foreach ($this->field as $field_name => $field_value) {
            $r .= '<tr>';
            $r .= '<td><b>' . $field_name . '</b></td>';
            $r .= '<td>' . $field_value . '</td>';
            $r .= '</tr>';
        }
        $r .= '</table>';
        return $r;
    }


}

?>
