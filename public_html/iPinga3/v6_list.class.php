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

/*
 * do things with array of v6_table objects
 */
class v6_list
{

    /** @var array $the_list array of v6_table objects such as those in v6_database_list class */
    public $the_list = array();

    /*
     * @param v6_database_list->the_list $the_list
     */
    function __construct($the_list = array())
    {
        $this->the_list = $the_list;
    }

    /*
     * Builds html for a <select> form element. Walks through the array of table object to build html for a <select> form element
     * @param string $field_name name of the column in the database table object(s) in $the_list
     * @param integer $selected_id the database id to make the actively selected element
     * @param boolean $add_first (default: false) ad the option 'select one...' to the start of the list
     * @param string $class css class name
     */
    public function html_as_select($select_name, $field_name, $selected_id = 0, $add_first = false, $class = '')
    {
        $html = '<select name="' . $select_name . '" id="' . $select_name . '"';
        if (!empty($class) == true) {
            $html .= ' class="' . $class . '"';
        }
        $html .= '>' . "\r\n";


        if ($add_first) {
            $html .= '<option value=0>Select one...</option>' . "\r\n";
        }

        foreach ($this->the_list as $t) {
            $html = $html . '<option value="' . $t->field['id'] . '"';
            if ($t->field['id'] == $selected_id) {
                $html = $html . ' selected="selected"';
            }
            $html = $html . '>' . $t->field[$field_name] . '</option>' . "\r\n";
        }

        $html = $html . '</select>' . "\r\n";

        return $html;
    }

    /*
     * Return json representation of the array of v6_table object's current field values
     */
    public function json()
    {
        $j = array();
        foreach ($this->the_list as $t) {
            $j[] = $t->field;
        }
        return json_encode($j);
    }


    /*
     * locate a table object within a array of table objects by looking at the id column
     * @param int $id
     * @returns mixed the table object if found otherwise false
     */
    public function record_by_field($field_name, $value)
    {
        foreach ($this->the_list as $r) {
            if ($r->$field_name == $value) return $r;
        }
        return false;
    }


    /*
     * look for a table object field_name=value pair and return record number
     * @returns int
     */
    public function record_number_by_field($field_name, $value)
    {
        $record_number = 0;
        foreach ($this->the_list as $t) {
            if ($t->$field_name == $value) {
                return $record_number;
            }
            $record_number++;
        }
        return 0;
    }


}
