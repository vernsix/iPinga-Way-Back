<?php
/** 
 * calendar class
 * @author Vern Six <Vern@VernSix.com>
 * @copyright Copyright (c) 2007-2011, Vernon E. Six, Jr. - All Rights Reserved
 * @package v6_calendar
 * @version 1.0
 * @since 1.0
 *
 */
defined('__VERN') or die('Restricted access');

 
class v6_Calendar{

	private function ShowDate( $month, $day, $year, $events=array(), $editdatelink )
	{
		
		$today = getdate();		
		if (($day == $today['mday']) && ($today['mon'] == $month) && ($today['year'] == $year) ) {
			$active = '_active';
		} else {
			$active = '';
		}	
		
		echo '<td class="cal_cell_day' .$active. '">';
		
		if (empty($editdatelink)) {
			echo $day;
		} else {	
			$thisdate = $year.'-'. substr('0'.$month,-2,2).'-'.substr('0'.$day,-2,2);
			echo '<a href="';				
			echo sprintf($editdatelink,	$thisdate);
			echo '" class="cal_edit_day' .$active. '">' .$day. '</a>';
		}

			
		
		if ( count($events) > 0 ) {
		
			echo '<table width="100%" class="cal_event_table' .$active. '">';				
			echo "\n";
			$tempdate = $year. '-'. substr('0'.$month,-2,2). '-'. substr('0'.$day,-2,2);
			foreach ( $events as $value ) {
				if ($value[0]==$tempdate)  {
					echo '<tr class="cal_event_row' .$active. '">';
					
					
					echo '<td class="cal_event_time' .$active. '">';
					if (!empty($value[3])) {
						echo '<a href="' .$value[3]. '" class="cal_event_desc' .$active. '">' .$value[1]. '</a>';
					} else {
						echo $value[1];					
					}					
					echo '</td>';
					
					
					echo '<td class="cal_event_desc' .$active. '">';
					if (empty($value[2])) 
					{
					  echo '&nbsp;';
					} else {
						if (!empty($value[3])) {
							echo '<a href="' .$value[3]. '" class="cal_event_desc' .$active. '">' .$value[2]. '</a>';
						} else {
							echo $value[2];					
						}	
					}					
					echo '</td>';	
					
					
					echo "</tr>\n";
				}
			}						
			echo "</table>\n";
		
		}
		echo "</td>\n\n";
	}
	
	
	/**
	* @param	string $date yyyy-mm-dd
	* @param	array $events [ (date, time, descrip, link) ] ===> date string yyyy-mm-dd   time string hh:mm	
	* @param	string $editdatelink link that will be passed the date via sprintf in the %s parameter   ie: '/editthisdate=%s'
	* @param	string $prevmonthlink
	* @param	string @nextmonthlink
	*/	
    function showCalendar( $date, $events=array(), $editdatelink='', $prevmonthlink='', $nextmonthlink='' ){

		$year = substr($date,0,4);
		$month = substr($date,5,2);
	
		// Get today, reference day, first day and last day info
		if (($year == 0) || ($month == 0)){
			$referenceDay    = getdate();
		} else {
			$referenceDay    = getdate(mktime(0,0,0,$month,1,$year));
		}
		$firstDay = getdate(mktime(0,0,0,$referenceDay['mon'],1,$referenceDay['year']));
		$lastDay  = getdate(mktime(0,0,0,$referenceDay['mon']+1,0,$referenceDay['year']));
	
		// Create a table with the necessary header informations
		echo '<table class="cal_month" width="80%">';
		echo '  <tr>';
		echo '	  <th class="cal_hdr_month" id="next_prev" colspan="2">'. $prevmonthlink. '</th>';		
		echo '    <th class="cal_hdr_month" colspan="3">' .$referenceDay['month']. ' ' .$referenceDay['year']. '</th>';
		echo '	  <th class="cal_hdr_month" id="next_prev"colspan="2">'. $nextmonthlink. '</th>';		
		echo '  </tr>';
		echo '  <tr class="cal_row_day">';
		echo '    <td class="cal_hdr_day" width="15%">Monday</td>';
		echo '    <td class="cal_hdr_day" width="15%">Tuesday</td>';
		echo '    <td class="cal_hdr_day" width="15%">Wednesday</td>';
		echo '    <td class="cal_hdr_day" width="15%">Thursday</td>';
		echo '    <td class="cal_hdr_day" width="15%">Friday</td>';
		echo '    <td class="cal_hdr_day" width="12%">Saturday</td>';
		echo '    <td class="cal_hdr_day" width="12%">Sunday</td>';
		echo '  </tr>';
	
	
		// Display the first calendar row with correct positioning
		echo '<tr>';
			if ($firstDay['wday'] == 0) {
				$firstDay['wday'] = 7;
			}
			// leave a few blank days for space from last month...
			for($i=1;$i<$firstDay['wday'];$i++){
				echo '<td>&nbsp;</td>';
			}
			$actday = 0;
			for($i=$firstDay['wday'];$i<=7;$i++){
				$actday++;
				$this->ShowDate( $month, $actday, $year, $events, $editdatelink );
			}
		echo '</tr>';
	
		//Get how many complete weeks are in the actual month
		$fullWeeks = floor(($lastDay['mday']-$actday)/7);
	
		for ($i=0;$i<$fullWeeks;$i++)
		{
			echo '<tr>';
				for ($j=0;$j<7;$j++)
				{
					$actday++;
					$this->ShowDate( $month, $actday, $year, $events, $editdatelink );
				}
			echo '</tr>';
		}
	
		//Now display the rest of the month
		if ($actday < $lastDay['mday']){
			echo '<tr>';
				for ($i=0; $i<7;$i++)
				{
					$actday++;
					
					if ($actday <= $lastDay['mday']){
						$this->ShowDate( $month, $actday, $year, $events, $editdatelink );
					}
					else {
						echo '<td>&nbsp;</td>';
					}
				}
			echo '</tr>';
		}
		
		echo '</table>';
	}
	
}
?>