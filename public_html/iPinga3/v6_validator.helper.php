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

Class v6_validator
{

    // @todo: add phrase translation ability. a simple associative array with my phrase as the key and whatever they want as the value

    /** @var string */
    public $message = '';

    /** @var array */
    public $vars = array();

    /** @var v6_template */
    public $template;

    /** @var array $queue */
    public $queue = array();


    public $set_hint_in_session = false;


    /**
     * @param array $vars
     * @param string $template optional: if passed, the template_hint vars will be set if validate fails
     */
    function __construct($vars,$template=null) // like $_POST[] or $_GET[]
    {
        $this->vars = $vars;
        if (isset($template)==true) {
            $this->template = $template;
        }
    }


    /**
     * @param string $var_name
     * @param string $hint
     */
    public function queue_TemplateHint( $var_name, $hint )
    {
        $this->queue[] = array( 'TemplateHint', $var_name, $hint );
    }

    /**
     * @param string $var_name
     * @param string $hint
     */
    public function SetTemplateHint( $var_name, $hint )
    {
        if (isset($this->template)==true) {
            $this->template->__set($var_name. '_hint',$hint);
        }

        if ($this->set_hint_in_session==true) {
            $h = str_replace('<br>','',trim($hint));
            if (empty($h)==true) {
                if (isset($_SESSION[$var_name. '_hint'])==true) {
                    unset($_SESSION[$var_name. '_hint']);
                }
            } else {
                $_SESSION[$var_name. '_hint'] = $h;
            }
        }

    }



    public function process_queue()
    {
        foreach( $this->queue as $q )
        {
            $type = $q[0];

            switch ($type) {
                case "number":
                    $this->check_number( $q[1], $q[2], $q[3], $q[4], $q[5] );
                    break;
                case "array":
                    $this->check_array( $q[1], $q[2], $q[3], $q[4] );
                    break;
                case "string":
                    $this->check_string( $q[1], $q[2], $q[3], $q[4], $q[5], $q[6] );
                    break;
                case "date":
                    $this->check_date( $q[1], $q[2], $q[3], $q[4], $q[5] );
                    break;
                case "time":
                    $this->check_time( $q[1], $q[2], $q[3], $q[4], $q[5] );
                    break;
                case "password":
                    $this->check_password( $q[1], $q[2], $q[3], $q[4], $q[5], $q[6] );
                    break;
                case "match":
                    $this->check_match( $q[1], $q[2], $q[3] );
                    break;
                case "email":
                    $this->check_match( $q[1], $q[2], $q[3] );
                    break;
                case "TemplateHint":
                    $this->SetTemplateHint( $q[1], $q[2] );
                    break;
            }

        }
    }



    /**
     * @param string $var_name
     * @param string $var_desc
     * @param float int $min
     * @param float $max
     * @param bool $required
     */
    public function queue_number( $var_name, $var_desc, $min, $max, $required = true )
    {
        $this->queue[] = array( 'number', $var_name, $var_desc, $min, $max, $required );
    }


    /**
     * @param string $var_name
     * @param string $var_desc
     * @param float int $min
     * @param float $max
     * @param bool $required
     */
    function check_number($var_name, $var_desc, $min, $max, $required = true)
    {
        $message = '';
        if ($required == true || ((isset($this->vars[$var_name])) && (strlen($this->vars[$var_name]) > 0))) {
            if (isset($this->vars[$var_name])) {
                if (!is_numeric($this->vars[$var_name])) {
                    $message = $var_desc . ' must be a number.';
                } elseif ($this->vars[$var_name] < $min) {
                    $message = $var_desc . ' must be greater than or equal to ' . $min . '.';
                } elseif ($this->vars[$var_name] > $max) {
                    $message = $var_desc . ' must be less than or equal to ' . $max . '.';
                }
            } else {
                $message = $var_desc . ' is undefined<br>';
            }
        }
        $this->SetTemplateHint($var_name, $message);
        if (strlen($message) > 0) {
            $this->message .= $message . '<br>';
        }
    }

    /**
     * @param string $var_name
     * @param string $var_desc
     * @param array $valid_choices
     * @param bool $required
     */
    public function queue_array( $var_name,$var_desc,$valid_choices,$required=true )
    {
        $this->queue[] = array( 'array', $var_name,$var_desc,$valid_choices,$required );
    }

    /**
     * @param string $var_name
     * @param string $var_desc
     * @param array $valid_choices
     * @param bool $required
     */
    function check_array($var_name,$var_desc,$valid_choices,$required = true)
    {
        $message = '';

        // if it's required or if it's set and the length > 0
        if ($required == true || ((isset($this->vars[$var_name])) && (strlen($this->vars[$var_name]) > 0))) {
            if (isset($this->vars[$var_name])) {
                if (array_search($this->vars[$var_name],$valid_choices)===false) {
                    $message = $var_desc . ' is invalid';
                }
            } else {
                $message = $var_desc . ' is undefined<br>';
            }
        }
        $this->SetTemplateHint($var_name, $message);
        if (strlen($message) > 0) {
            $this->message .= $message . '<br>';
        }
    }






    /**
     * @param string $var_name
     * @param string $var_desc
     * @param int $min_length
     * @param int $max_length
     * @param bool $required
     * @param string $reg_exp
     */
    function queue_string($var_name, $var_desc, $min_length, $max_length, $required = true, $reg_exp = '/^[.!@&<>"=;$-_ 0-9a-zA-Z\f\n\r\t\']+$/')
    {
        $this->queue[] = array( 'string', $var_name, $var_desc, $min_length, $max_length, $required, $reg_exp );
    }

    /**
     * @param string $var_name
     * @param string $var_desc
     * @param int $min_length
     * @param int $max_length
     * @param bool $required
     * @param string $reg_exp
     */
//    function check_string($var_name, $var_desc, $min_length, $max_length, $required = true, $reg_exp = '/^[.!@&<>"=;$-_ 0-9a-zA-Z\']+$/')
    function check_string($var_name, $var_desc, $min_length, $max_length, $required = true, $reg_exp = '/^[.!@&<>"=;$-_ 0-9a-zA-Z\f\n\r\t\']+$/')
    {
        $message = '';
        if ($required == true || ((isset($this->vars[$var_name])) && (strlen($this->vars[$var_name]) > 0))) {
            if (!isset($this->vars[$var_name])) {
                $message = $var_desc . ' is invalid.';
            } elseif (!is_string($this->vars[$var_name])) {
                $message = $var_desc . ' is invalid.';
            } elseif (strlen($this->vars[$var_name]) < $min_length) {
                $message = $var_desc . ' is too short.';
            } elseif (strlen($this->vars[$var_name]) > $max_length) {
                $message = $var_desc . ' is too long.';
            } elseif (!preg_match($reg_exp, $this->vars[$var_name])) {
                $message = $var_desc . ' contains invalid characters.';
            }
        }
        $this->SetTemplateHint($var_name, $message);
        if (strlen($message) > 0) {
            $this->message .= $message . '<br>';
        }
    }



    /**
     * @param string $var_name
     * @param string $var_desc
     * @param string $min_date 2011-12-17 format
     * @param string $max_date 2011-12-17 format
     * @param bool $required
     */
    function queue_date($var_name, $var_desc, $min_date, $max_date, $required = true)
    {
        $this->queue[] = array( 'date', $var_name, $var_desc, $min_date, $max_date, $required );
    }


    /**
     * @param string $var_name
     * @param string $var_desc
     * @param string $min_date 2011-12-17 format
     * @param string $max_date 2011-12-17 format
     * @param bool $required
     */
    function check_date($var_name, $var_desc, $min_date, $max_date, $required = true)
    {
        $message = '';
        if ($required == true || ((isset($this->vars[$var_name])) && (strlen($this->vars[$var_name]) > 0))) {
            if (!isset($this->vars[$var_name])) {
                $message = $var_desc . ' is invalid.';
            } elseif (strlen($this->vars[$var_name]) <> 10) {
                $message = $var_desc . ' is invalid.';
            } else {
                $date = $this->vars[$var_name];
                $yyyy = substr($date, 0, 4);
                $mm = substr($date, 5, 2);
                $dd = substr($date, 8, 2);
                if ($dd != "" && $mm != "" && $yyyy != "") {
                    if (checkdate($mm, $dd, $yyyy) == true) {
                        if ($date < $min_date) {
                            $message = $var_desc . ' is before ' . $min_date . '.';
                        } elseif ($date > $max_date) {
                            $message = $var_desc . ' is after ' . $max_date . '.';
                        }
                    } else {
                        $message = $var_desc . ' is invalid.';
                    }
                    ;
                }
            }
        }
        $this->SetTemplateHint($var_name, $message);
        if (strlen($message) > 0) {
            $this->message .= $message . '<br>';
        }
    }



    /**
     * @param string $var_name
     * @param string $var_desc
     * @param string $min_time
     * @param string $max_time
     * @param bool $required
     */
    function queue_time($var_name, $var_desc, $min_time, $max_time, $required = true)
    {
        $this->queue[] = array( 'time', $var_name, $var_desc, $min_time, $max_time, $required );
    }

    /**
     * @param string $var_name
     * @param string $var_desc
     * @param string $min_time
     * @param string $max_time
     * @param bool $required
     */
    function check_time($var_name, $var_desc, $min_time, $max_time, $required = true)
    {
        $message = '';
        if ($required == true || ((isset($this->vars[$var_name])) && (strlen($this->vars[$var_name]) > 0))) {
            if (!isset($this->vars[$var_name])) {
                $message = $var_desc . ' is invalid.';
            } elseif (strlen($this->vars[$var_name]) <> 5) {
                $message = $var_desc . ' is invalid.';
            } elseif (preg_match("/^(([1-9]{1})|([0-1][0-9])|([1-2][0-3]))\.([0-5][0-9])$/", $this->vars[$var_name]) === false) {
                $message = $var_desc . ' is invalid.';
            } else {
                $time = $this->vars[$var_name];
                if ($time < $min_time) {
                    $message = $var_desc . ' is before ' . $min_time . '.';
                } elseif ($time > $max_time) {
                    $message = $var_desc . ' is after ' . $max_time . '.';
                }
            }
        }
        $this->SetTemplateHint($var_name, $message);
        if (strlen($message) > 0) {
            $this->message .= $message . '<br>';
        }
    }





    /**
     * @param string $var_name
     * @param string $var_desc
     * @param int $min_length
     * @param int $max_length
     * @param bool $required
     * @param bool $strong
     */
    function queue_password($var_name, $var_desc, $min_length, $max_length, $required = true, $strong = false)
    {
        $this->queue[] = array( 'password', $var_name, $var_desc, $min_length, $max_length, $required, $strong );
    }

    /**
     * @param string $var_name
     * @param string $var_desc
     * @param int $min_length
     * @param int $max_length
     * @param bool $required
     * @param bool $strong
     */
    function check_password($var_name, $var_desc, $min_length, $max_length, $required = true, $strong = false)
    {
        if ($strong == true) {
            $reg_exp = "/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/";
        } else {
            $reg_exp = "/^[-_0-9a-zA-Z!@#$%^&*()+=~\']+$/";
        }
        $this->check_string($var_name, $var_desc, $min_length, $max_length, $required, $reg_exp);
    }



    /**
     * @param string $var_name1
     * @param string $var_name2
     * @param string $var_desc
     */
    function queue_match($var_name1, $var_name2, $var_desc)
    {
        $this->queue[] = array( 'match', $var_name1, $var_name2, $var_desc );
    }

    /**
     * @param string $var_name1
     * @param string $var_name2
     * @param string $var_desc
     */
    function check_match($var_name1, $var_name2, $var_desc)
    {
        $message = '';
        if (($this->vars[$var_name1] <> $this->vars[$var_name2]) || (strlen($this->vars[$var_name1]) <> strlen($this->vars[$var_name2]))) {
            $message = $var_desc . ' do not match.';
        }
        $this->SetTemplateHint($var_name1, $message);
        if (strlen($message) > 0) {
            $this->message .= $message . '<br>';
        }
    }





    /**
     * @param string $var_name
     * @param string $var_desc
     * @param bool $required
     */
    function queue_email($var_name, $var_desc, $required = true)
    {
        $this->queue[] = array( 'email', $var_name, $var_desc, $required );
    }

    /**
     * @param string $var_name
     * @param string $var_desc
     * @param bool $required
     */
    function check_email($var_name, $var_desc, $required = true)
    {
        $message = '';
        if ($required == true || ((isset($this->vars[$var_name])) && (strlen($this->vars[$var_name]) > 0))) {
            if (!isset($this->vars[$var_name])) {
                $message = $var_desc . ' is invalid.';
            }
            if (!is_string($this->vars[$var_name])) {
                $message = $var_desc . ' is invalid.';
            }
            if (!preg_match('/^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$/', $this->vars[$var_name])) {
                $message = $var_desc . ' is not a valid email address';
            }
        }
        $this->SetTemplateHint($var_name, $message);
        if (strlen($message) > 0) {
            $this->message .= $message . '<br>';
        }
    }


    /**
     * if the hidden field doesn't match or isn't set, then we need to create one and go into display
     * @return boolean $ReadyToSave
     */
    function check_form_token()
    {
        $message = 'Invalid form token!';
        $ReadyToSave = false;
        if (isset($this->vars['form_token'], $_SESSION['form_token'])) {
            if ($this->vars['form_token'] == $_SESSION['form_token']) {
                $message = '';
                $ReadyToSave = true;
            }
        }
        $this->SetTemplateHint('form_token_hint', $message);
        if (strlen($message) > 0) {
            $this->message .= $message . '<br>';
        }
        return $ReadyToSave;
    }

    function set_form_token()
    {
        $form_token = md5(uniqid('form', true));
        $this->template->form_token = $form_token;
        $_SESSION['form_token'] = $form_token;
    }

}


?>