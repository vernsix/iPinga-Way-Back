<?php
/**
 * Created by Vern
 * Date: 12/4/11
 * Time: 3:40 PM
 */


/*
* deprecated - was originally for a specific client use... thought it might be handy for data driven graphics lists
*/
function GraphicUrl($the_list, $code, $border = 0, $title = '')
{

    if (is_numeric($code) == true) {
        $field_name = 'id';
    } else {
        $field_name = 'code';
    }

    foreach ($the_list as $graphic) {
        if ($graphic->field[$field_name] == $code) {
            $url = '<img src="' . $graphic->field['url'] . '" border=' . $border . ' title="' . $title . '"/>';
            return $url;
        }
    }

    return '<img src="/images/NotFound.jpg">';
}



/*
 * somewhat deprecated... I will come back to revisit this one soon
 * @todo finish documenting ComboBox() function
 */
function ComboBox( $the_list, $field_name, $combo_name, $selected, $none_ok = false ) {
	$html = '<select name="'.$field_name.'" id="'.$field_name.'">';
	if ($none_ok == true) {
		$html = $html. '<option value=" "';
		if (""==trim($selected)) {
			$html = $html. ' selected="selected"';
		}
		$html = $html.  '>Default</option>';
	}
	foreach( $the_list as $tbl ) {
		if ($tbl->field['combo_name'] == $combo_name) {
			$html = $html. '<option value="'. $tbl->field['choice_text']. '"';
			if ($tbl->field['choice_text']==$selected) {
				$html = $html. ' selected="selected"';
			}
			$html = $html.  '>'. $tbl->field['choice_text']. '</option>';
		}
	}
	$html = $html . '</select>';
	return $html;
}



/*
 * somewhat deprecated... I will come back to revisit this one soon
 * @todo finish documenting CheckBoxes() function
 */
function CheckBoxes( $the_list, $field_name, $combo_name ) {
	$html = '';
	foreach ($the_list as $tbl) {
		if ($tbl->field['combo_name'] == $combo_name) {
			$html .= '<input type="checkbox" id="'. $field_name .'-'. $tbl->field['id'] .'" name="'. $field_name. '-'. $tbl->field['id'] .'">'. $tbl->field['choice_text']. '<br />';
		}
	}
	return $html;
}


/*
 * somewhat deprecated... I will come back to revisit this one soon
 * @todo finish documenting FindComboBoxRecord() function
 */
function FindComboBoxRecord( $the_list, $combo_name, $choice_text ) {
	foreach( $the_list as $r ) {
		if ( ($r->field['combo_name']==$combo_name) && (trim($r->field['choice_text'])==trim($choice_text)) ) return $r;
	}
}


/*
 * somewhat deprecated... I will come back to revisit this one soon
 * @todo finish documenting ComboBoxBySequence() function
 */
function ComboBoxBySequence( $the_list, $combo_name, $sequence ) {
	$html = '<select name="'.$combo_name.'">';
	foreach( $the_list as $tbl ) {
		if ($tbl->field['combo_name'] == $combo_name) {
			$html = $html. '<option value="'. $tbl->field['choice_text']. '"';
			if ($tbl->field['sequence']==$sequence) {
				$html = $html. ' selected="selected"';
			}
			$html = $html.  '>'. $tbl->field['choice_text'] . '</option>';
		}
	}
	$html = $html . '</select>';
	return $html;
}



























?>