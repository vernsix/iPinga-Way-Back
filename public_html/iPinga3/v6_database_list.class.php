<?php
/**
 * Database list class
 * @author Vern Six <Vern@VernSix.com>
 * @version 2.0
 * @since 1.0
 */
defined('__VERN') or die('Restricted access');

Class v6_database_list
{

    /**
     * @var string table name we are working with. Set in the constructor call
     */
    public $table_name;

    /**
     * @var array Array of table objects in the list.  Each table object will contain precisely one row of data from the table
     */
    public $the_list = array();

    /**
     * create an instance of the database_list class
     *
     * @param class::db reference to the db object typically this is from the registry like $this->registry->db
     * @param string $table_name name of the table to build the list from
     */
    public function __construct($table_name)
    {
        $this->table_name = $table_name;
    }

    /**
     * perform the actual read from the database and populate this instance of the list. select statement MUST have a
     * field called "id" as a column name in the result set!
     * @param $sql select statement to grab rows from the database
     */
    function read_from_db($sql)
    {
        global $registry;
        $this->the_list = array(); // start fresh with an empty list
        try {
            foreach ($registry->db->query($sql) as $row) {
                $tbl = new v6_table($this->table_name);
                $tbl->load_by_id($row['id']);
                $this->the_list[] = $tbl;
            }
        } catch (PDOException $e) {
            /**
             * @todo clean up error catch exposure
             */
            echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
        }
    }

    /**
     * load a list for the specified client_id. table must have a column named client_id
     *
     * @param integer $client_id
     * @param string $order_by optional: orderby field for sql select statement
     */
    public function load($client_id=-1, $order_by = "id")
    {
        if ($client_id == -1) { // include everything for all clients and if client_id doesn't exist in database
            $sql = sprintf('select id from %s order by %s', $this->table_name, $order_by);
        } else {
            $sql = sprintf('select id from %s where client_id=%d order by %s', $this->table_name, $client_id, $order_by);
        }
        $this->read_from_db($sql);
    }


    /**
     * load a list for the specified id.  table must have a column named id
     * @since 1.1 now accepts -1 to load across all ids
     * @version 1.1
     *
     * @param integer $id
     * @param string $order_by optional: orderby field for sql select statement   Default: "id"
     */
    public function load_by_id($id=-1, $order_by = "id")
    {
        if ($id == -1) { // include everything for all ids and if id doesn't exist in database
            $sql = sprintf('select id from %s order by %s', $this->table_name, $order_by);
        } else {
            $sql = sprintf('select id from %s where id=%d order by %s', $this->table_name, $id, $order_by);
        }
        $this->read_from_db($sql);
    }


    /**
     * load a list for the specified "other id".  you have to tell the function the column name that serves as the id
     * @since 1.1 now accepts -1 to load across all ids
     * @version 1.1
     *
     * @param string $other_id name of table column that serves as id column
     * @param integer $id
     * @param string $order_by optional: orderby field for sql select statement Default: "id"
     */
    public function load_by_other_id($other_id, $id, $order_by = "id")
    {
        $sql = sprintf('select id from %s where %s=%d order by %s', $this->table_name, $other_id, $id, $order_by);
        $this->read_from_db($sql);
    }


    public function load_by_custom_where($where, $order_by = "id")
    {
        $sql = sprintf('select id from %s where %s order by %s', $this->table_name, $where, $order_by);
        $this->read_from_db($sql);
    }


    /**
     * quick and dirty function delete a record from the table. This really shouldn't be used much, but since we already
     * have the handle open, I allow it even though I prefer using a table object instead of a database_list object to
     * do my deletes.
     *
     * @version 1.1
     *
     * @param string $other_id name of table column that serves as id column
     * @param integer $id id to delete
     */
    public function delete_by_other_id($other_id, $id)
    {
        global $registry;
        $sql = sprintf('delete from %s where %s=%d', $this->table_name, $other_id, $id);
        try {
            $registry->db->exec($sql);
        } catch (PDOException $e) {
            echo $e->getMessage() . '<br>' . $sql . '<br><hr>';
        }
    }

}

?>