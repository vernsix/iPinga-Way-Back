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
 * do things with zip codes
 */
class v6_zip
{

    /** @var string */
    private $zip_radius_table_name;

    /** @var v6_table */
    private $my_zip_record;


    /**
     * @param string $zip_table_name
     */
    function __construct($zip_table_name='zip_radius')
    {
        $this->zip_radius_table_name = $zip_table_name;
        $this->my_zip_record = new v6_table($zip_table_name);
    }


    /**
     * @param string $myzip
     * @param int $miles
     * @return array v6_databaselist->the_list
     */
    function ZipsWithinMiles( $myzip, $miles )
    {

        // first find out my lat/lon
        $this->my_zip_record->load_by_secondary_key('zip_code',$myzip);

        $ziplist = new v6_database_list($this->zip_radius_table_name);

        $where = sprintf( "(SQRT((69.172*(%f-zip_radius_table_name.latitude))*(69.172*(%f-zip_radius_table_name.latitude))+(53.0*(%f-zip_radius_table_name.longitude))*(53.0*(%f-zip_radius_table_name.longitude)))<=%f)", $this->my_zip_record->latitude, $this->my_zip_record->latitude, $this->my_zip_record->longitude, $this->my_zip_record->longitude, $miles );

        $where = str_replace('zip_radius_table_name',$this->zip_radius_table_name,$where);

        $ziplist->load_by_custom_where($where);

        return $ziplist->the_list;

    }

    /**
     * @param string $myzip
     * @param int $miles
     * @return array zips
     */
    function ZipsWithinMilesZipsOnly( $myzip, $miles )
    {
        $zips = $this->ZipsWithinMiles($myzip,$miles);

        $z = array();
        foreach($zips as $table) {
            $z[] = $table->zip_code;
        }
        return $z;

    }

    function ZipsWithinMilesAsWhereClause( $myzip, $miles )
    {
        $z = $this->ZipsWithinMilesZipsOnly($myzip,$miles);
        return "('" . implode("','",$z). "')";
    }





}
