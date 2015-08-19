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

/**
 * Class v6_ajax
 * deprecated! 12/26/2014
 */
class v6_ajax
{
    /** @var array */
    public $data;

    /** @var \v6_table */
    public $record;


    function get_record($table_name, $var_name, $reqd_admin_level = 0, $must_be_logged_id = false)
    {
        global $registry;

        // start fresh
        $this->data = array();
        $this->data['status'] = AJAX_NO_ERROR;
        $this->data['message'] = '';

        if (isset($this->record) == true) {
            unset($this->record);
        }

        $mgr = new v6_manager();
        $mgr->UserIsLoggedIn(false);

        if (isset($must_be_logged_id) && ($must_be_logged_id == true) && ($mgr->IsLoggedIn == false)) {
            $this->data['status'] = AJAX_ACCESS_DENIED;
            $this->data['message'] = 'Access Denied (AGR-1)';
            return $this->data['status'];
        }

        if (($reqd_admin_level == 2) && ($mgr->is_almighty == false)) {
            $this->data['status'] = AJAX_ACCESS_DENIED;
            $this->data['message'] = 'Access Denied (AGR-2)';
            return $this->data['status'];
        }

        if (($reqd_admin_level == 1) && (($mgr->is_almighty == false) && ($mgr->is_admin == false))) {
            $this->data['status'] = AJAX_ACCESS_DENIED;
            $this->data['message'] = 'Access Denied (AGR-3)';
            return $this->data['status'];
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->data['status'] = AJAX_ACCESS_DENIED;
            $this->data['message'] = 'Access Denied (AGR-4)';
            return $this->data['status'];
        }

        $registry->helper->load('v6_validator');
        $validator = new v6_validator($_GET);

        // make sure the querystring is a valid number...
        $validator->CheckNumber($var_name, 'Id', 0, 999999);
        $message = $validator->message;

        if (empty($message) == false) {
            $this->data['status'] = AJAX_VALIDATION_FAILURE;
            $this->data['message'] = $message;
            return $this->data['status'];
        }

        $this->record = new v6_table($table_name);
        $this->record->load_by_id($_GET[$var_name]);

        $this->data['status'] = AJAX_RECORD_DATA;
        $this->data['message'] = 'Record Data';

        return $this->data['status'];

    }


    function delete_record($table_name, $var_name, $reqd_admin_level = 0, $must_be_logged_id = false)
    {
        global $registry;

        // start fresh
        $this->data = array();
        $this->data['status'] = AJAX_NO_ERROR;
        $this->data['message'] = '';

        if (isset($this->record) == true) {
            unset($this->record);
        }

        $mgr = new v6_manager();
        $mgr->UserIsLoggedIn(false);

        if (isset($must_be_logged_id) && ($must_be_logged_id == true) && ($mgr->IsLoggedIn == false)) {
            $this->data['status'] = AJAX_ACCESS_DENIED;
            $this->data['message'] = 'Access Denied (ADR-1)';
            return $this->data['status'];
        }

        if (($reqd_admin_level == 2) && ($mgr->is_almighty == false)) {
            $this->data['status'] = AJAX_ACCESS_DENIED;
            $this->data['message'] = 'Access Denied (ADR-2)';
            return $this->data['status'];
        }

        if (($reqd_admin_level == 1) && (($mgr->is_almighty == false) && ($mgr->is_admin == false))) {
            $this->data['status'] = AJAX_ACCESS_DENIED;
            $this->data['message'] = 'Access Denied (ADR-3)';
            return $this->data['status'];
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->data['status'] = AJAX_ACCESS_DENIED;
            $this->data['message'] = 'Access Denied (ADR-4)';
            return $this->data['status'];
        }

        $registry->helper->load('v6_validator');
        $validator = new v6_validator($_POST);

        // make sure the querystring is a valid number...
        $validator->CheckNumber($var_name, 'Id', 0, 999999);
        $message = $validator->message;

        if (empty($message) == false) {
            $this->data['status'] = AJAX_VALIDATION_FAILURE;
            $this->data['message'] = $message;
            return $this->data['status'];
        }

        $this->record = new v6_table($table_name);
        $this->record->delete_by_id($_POST[$var_name]);

        $this->data['status'] = AJAX_RECORD_DELETED;
        $this->data['message'] = 'Record Deleted';

        return $this->data['status'];

    }

    function send_as_xml($include_links = false)
    {
        ob_get_clean();
        header("Content-Type:text/xml"); // case is crucial!

        echo <<<EOD
<?xml version="1.0"?>
<response>

EOD;

        if (isset($this->data) == true) {
            foreach ($this->data as $key => $value) {
                echo '<' . $key . '>';
                echo htmlspecialchars(stripslashes($value));
                echo '</' . $key . ">\r\n";
            }
        }


        if (isset($this->record) == true) {

            foreach ($this->record->field as $field_name => $value) {
                echo '<' . trim($field_name) . '>';
                echo htmlspecialchars(stripslashes($value));
                echo '</' . trim($field_name) . '>'. "\r\n";
            }

            if ($include_links == true) {
                $id = (string)$this->record->id;
                echo <<<EOD
                <links>
       			    <div class="ui-widget">
       			    <ul id="icons" class="ui-widget ui-helper-clearfix">
       			    <li class="ui-state-default ui-corner-all" title="Edit"><span class="ui-icon ui-icon-pencil" onclick="linkEdit($id);"></span></li>
       			    <li class="ui-state-default ui-corner-all" title="Delete"><span class="ui-icon ui-icon-trash" onclick="linkDelete($id);"></span></li>
       			    </ul>
       			    </div>
                </links>
EOD;
            }
        }

        echo <<<EOD

</response>
EOD;

    }

}
