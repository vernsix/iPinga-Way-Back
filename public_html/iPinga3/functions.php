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

function v6_LimitWords($string, $word_limit){
    $words = explode(' ', $string, ($word_limit + 1));
    if(count($words) > $word_limit)
        array_pop($words);
    return implode(' ', $words);
}





/**
 *
 * @param mixed $host
 * @param int $port
 * @param int $timeout
 * @return bool
 */
function v6_ping($host,$port=80,$timeout=6)
{
    $fsock = fsockopen($host, $port, $errno, $errstr, $timeout);
    if ( ! $fsock )
    {
        return FALSE;
    }
    else
    {
        return TRUE;
    }
}






/**
 * fixes the stupid issue with PHP's empty() that can only check vars not array elements!
 *
 * @param mixed $var
 * @return bool
 */
function v6_empty($var)
{
    return empty($var);
}

/**
 * Returns a random string of specified length
 *
 * @param int $length
 * @param string $validCharacters
 *
 * @return string
 */
function v6_RandomString($length = 8, $validCharacters = "abcdefghijklmnpqrstuxyvwzABCDEFGHIJKLMNPQRSTUXYVWZ+-#&@!")
{
    $validCharNumber = strlen($validCharacters);
    $result          = "";
    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $validCharNumber - 1);
        $result .= $validCharacters[$index];
    }
    return $result;
}



/**
 * walks through a list of v6_table objects.  Returns the one with the correct id
 *
 * @param array $the_list
 * @param int $id
 *
 * @return int
 */
function v6_GetFromTheList($the_list, $id)
{
    $result = -1;
    foreach ($the_list as $key => $table)
    {
        if ($table->id == $id) {
            $result = $key;
        }
    }
    return $result;
}


/**
 * format a value in xml... sometimes it's just handier this way.
 *
 * @param string $tag_name
 * @param string $tag_value
 */
function v6_xtag($tag_name, $tag_value)
{
    return '<' . $tag_name . '>' . htmlspecialchars(stripslashes($tag_value)) . '</' . $tag_name . '>' . "\r\n";
}


/**
 * reads the contents of a disk file and returns it as a string
 *
 * @param string $the_file fully qualified file name. No checking is performed
 *
 * @returns string $contents the contents of the $the_file disk file
 */
function v6_FileContents($the_file)
{
    $filename = __WEBSITE_ROOT_PATH . DS . 'views' . DS . $the_file;
    $handle   = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    return $contents;
}


/**
 * Writes an HTML <SELECT> based on an array.  Makes it handy to hand code html
 * @param string $select_name
 * @param string $selected_value
 * @param $array
 * @param string $class
 * @param bool $include_select_one
 * @return string
 */
function v6_SelectFromArray($select_name, $selected_value, $array, $class = "", $include_select_one = true)
{
    $html = '<select name="' . $select_name . '" id="' . $select_name . '"';
    if (empty($class) == false) {
        $html .= 'class="' . $class . '"';
    }
    $html .= '>';
    if ((empty($selected_value) == true) && ($include_select_one == true)) {
        $html .= '<option value="" selected="selected">Select one...</option>';
    }
    foreach ($array as $key => $value) {
        $html .= '<option value="' . $key . '"';
        if ($key == $selected_value) {
            $html .= ' selected="selected"';
        }
        $html .= '>' . $value . '</option>';
    }
    $html .= '</select>';
    return $html;
}

/**
 * @return array
 */
function v6_ArrayOfStateAbbreviations()
{
    $result = array();
    $states = v6_ArrayOfStates();
    foreach ($states as $state_abbreviation => $state_name)
    {
        $result[] = $state_abbreviation;
    }
    return $result;
}

/**
 * @return array
 */
function v6_ArrayOfStates()
{
    $states       = array();
    $states["AL"] = "Alabama";
    $states["AK"] = "Alaska";
    $states["AZ"] = "Arizona";
    $states["AR"] = "Arkansas";
    $states["CA"] = "California";
    $states["CO"] = "Colorado";
    $states["CT"] = "Connecticut";
    $states["DE"] = "Delaware";
    $states["DC"] = "District Of Columbia";
    $states["FL"] = "Florida";
    $states["GA"] = "Georgia";
    $states["HI"] = "Hawaii";
    $states["ID"] = "Idaho";
    $states["IL"] = "Illinois";
    $states["IN"] = "Indiana";
    $states["IA"] = "Iowa";
    $states["KS"] = "Kansas";
    $states["KY"] = "Kentucky";
    $states["LA"] = "Louisiana";
    $states["ME"] = "Maine";
    $states["MD"] = "Maryland";
    $states["MA"] = "Massachusetts";
    $states["MI"] = "Michigan";
    $states["MN"] = "Minnesota";
    $states["MS"] = "Mississippi";
    $states["MO"] = "Missouri";
    $states["MT"] = "Montana";
    $states["NE"] = "Nebraska";
    $states["NV"] = "Nevada";
    $states["NH"] = "New Hampshire";
    $states["NJ"] = "New Jersey";
    $states["NM"] = "New Mexico";
    $states["NY"] = "New York";
    $states["NC"] = "North Carolina";
    $states["ND"] = "North Dakota";
    $states["OH"] = "Ohio";
    $states["OK"] = "Oklahoma";
    $states["OR"] = "Oregon";
    $states["PA"] = "Pennsylvania";
    $states["RI"] = "Rhode Island";
    $states["SC"] = "South Carolina";
    $states["SD"] = "South Dakota";
    $states["TN"] = "Tennessee";
    $states["TX"] = "Texas";
    $states["UT"] = "Utah";
    $states["VT"] = "Vermont";
    $states["VA"] = "Virginia";
    $states["WA"] = "Washington";
    $states["WV"] = "West Virginia";
    $states["WI"] = "Wisconsin";
    $states["WY"] = "Wyoming";
    return $states;
}

/**
 * @param $selected_state
 * @return string
 */
function v6_StateOptionList($selected_state)
{
    if (empty($selected_state)==true) {
        $html = '<option value="" selected="selected">Select a State</option>';
    } else {
        $html = '';
    }
    $states = v6_ArrayOfStates();
    foreach ($states as $key => $value) {
        $html = $html . '<option value="' . $key . '"';
        if ($key == $selected_state) {
            $html = $html . ' selected="selected"';
        }
        $html = $html . '>' . $value . '</option>';
    }
    return $html;
}


function v6_FormGather($table)
{
    foreach ($table->field_types as $field_name => $field_type) {
        if (isset($_POST[$field_name]) == true) {
            $table->field[$field_name] = $_POST[$field_name];
        }
    }
}

/*
* simple little function to put contents in comments area of html
*/
function v6_echo($var_name, $var)
{
    echo '<!-- ' . $var_name . ': [' . var_export($var, true) . '] -->' . "\r\n";
}


/**
 * Adjust a datatime varible by specified number of hours
 *
 * @param string $datetime yyyy-mm-dd hh:mm:ss
 * @param int $offset number of hours to offset plus or minus
 *
 * @return string yyyy-mm-dd hh:mm:ss
 */
function v6_AdjustTime($offset, $datetime = '')
{
    if (empty($datetime) == true) {
        $datetime = date("Y-m-d H:i:s");
    }
    return date("Y-m-d H:i:s", strtotime($datetime) + ($offset * 3600));
}


/**
 * makes sure a string is a valid date (doesn't do anything for time!)
 */
function v6_IsValidDate($date)
{
    if (!isset($date) || $date == "") {
        return false;
    }
    if (strlen($date) <> 10) {
        return false;
    }
    $yyyy = substr($date, 0, 4);
    $mm   = substr($date, 5, 2);
    $dd   = substr($date, 8, 2);
    if ($dd != "" && $mm != "" && $yyyy != "") {
        return checkdate($mm, $dd, $yyyy);
    }
    return false;
}

/**
 * returns the first day of the month for the specified date as a MySql datetime string
 */
function v6_StartOfMonth($date)
{
    $yyyy = substr($date, 0, 4);
    $mm   = substr($date, 5, 2);
    $dd   = substr($date, 8, 2);
    return $yyyy . '-' . $mm . '-01 00:00:00';
}

/**
 * returns last day of the month for the specified date as a MySql datetime string
 */
function vernEndOfMonth($date)
{
    $yyyy = substr($date, 0, 4);
    $mm   = substr($date, 5, 2);
    $dd   = substr($date, 8, 2);
    return date("Y-m-d H:i:s", mktime(23, 59, 59, $mm + 1, 0, $yyyy));
}




/**
 * Sanitize values received from the form. Prevents SQL injection
 *
 * @param string $string The string value to clean
 *
 * @returns string the cleaned string
 */
function v6_Clean($string)
{
    $string = @trim($string);
    if (get_magic_quotes_gpc()) {
        $string = stripslashes($string);
    }
    return $string;
}

/**
 * stops the current program, displays the error message and the call stack
 */
function v6_Die($sError)
{
    echo "<hr /><div>" . $sError . "<br /><table border='1' cellpadding=10 cellspacing=10>";
    $sOut       = "";
    $aCallstack = debug_backtrace();

    echo "<thead><tr><th>file</th><th>line</th><th>function</th>" .
        "</tr></thead>";
    foreach ($aCallstack as $aCall)
    {
        if (!isset($aCall['file'])) $aCall['file'] = '[PHP Kernel]';
        if (!isset($aCall['line'])) $aCall['line'] = '';

        echo "<tr><td>{$aCall["file"]}</td><td>{$aCall["line"]}</td>" .
            "<td>{$aCall["function"]}</td></tr>";
    }
    echo "</table></div><hr /></p>";
    die();
}


function YesNo($bool_value)
{
    if ($bool_value == true) {
        return 'Yes';
    } else {
        return 'No';
    }
}

/**
 * @param string $time hh:mm format
 */
function v6_24to12($time)
{
    return date("g:ia", mktime(substr($time, 0, 2), substr($time, 3, 2)));
}

# Transform hours like "1:45" into the total number of minutes, "105".
function v6_HoursToMinutes($hours)
{
    if (strstr($hours, ':')) {
        # Split hours and minutes.
        $separatedData = split(':', $hours);

        $minutesInHours    = $separatedData[0] * 60;
        $minutesInDecimals = $separatedData[1];

        $totalMinutes = $minutesInHours + $minutesInDecimals;
    }
    else
    {
        $totalMinutes = $hours * 60;
    }

    return $totalMinutes;
}

# Transform minutes like "105" into hours like "1:45".
function v6_MinutesToHours($minutes)
{
    $hours          = floor($minutes / 60);
    $decimalMinutes = $minutes - floor($minutes / 60) * 60;

    # Put it together.
    $hoursMinutes = sprintf("%d:%02.0f", $hours, $decimalMinutes);
    return $hoursMinutes;
}



function v6_IsMobileBrowser()
{
    // if the user specified full site, then look for the cookie and forget testing the user-agent
    if (isset($_COOKIE['full_site']) == false) {

        $mobile_browser = '0';

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
        }

        $mobile_ua     = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'oper', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
            'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
            'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-');

        if (in_array($mobile_ua, $mobile_agents)) {
            $mobile_browser++;
        }

        if (isset($_SERVER['ALL_HTTP']) == true) {
            if (strpos(strtolower($_SERVER['ALL_HTTP']), 'OperaMini') > 0) {
                $mobile_browser++;
            }
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') > 0) {
            $mobile_browser = 0;
        }

        if ($mobile_browser > 0) {
            return true;
        }

    } // cookie set for override mobile and show full site

    return true; // for testing it's always true.  Should be set back to false here;

}

function v6_textlog($msg='') {
    $fh = fopen('\vernsix.php', 'a') or die("can't open file vernsix.php");
    fseek($fh, 0, SEEK_END);
    fwrite($fh, date('Y-m-d G:i:s'). ' - ' . $_SERVER['REMOTE_ADDR']. ' = ');
    fwrite($fh, $msg . "\r\n");
    fclose($fh);
}



?>