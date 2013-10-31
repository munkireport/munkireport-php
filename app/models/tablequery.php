<?php

class Tablequery {
    
    private $cfg = array();

    function __construct($cfg='')
    {
        $this->cfg = $cfg;
    }
    
	// ------------------------------------------------------------------------

	/**
	 * Retrieve all entries for serial
	 *
	 * @param string serial
	 * @return array
	 * @author abn290
	 **/
    function fetch($cfg)
    {
		// Get total records
        $ref_obj = new Machine();
        $iTotal = $ref_obj->count();

        $dbh = getdbh();

        // Get tables from column names
        $tables = array('machine' => 1);
        $formatted_columns = array();
        foreach($cfg['cols'] AS $pos => $name)
        {
            $tbl_col_array = explode('#', $name);
            if(count($tbl_col_array) == 2)
            {
                // Store table name
                $tables[$tbl_col_array[0]] = 1;
                // Format column name
                $formatted_columns[$pos] = sprintf('`%s`.`%s`', 
                    $tbl_col_array[0], $tbl_col_array[1]);
            }
            else
            {
                $formatted_columns[$pos] = sprintf('`%s`', $name);
            }
        }

        // Select
        $select = "SELECT ".implode(',', $formatted_columns);

        $tbl_list = array_keys($tables);

        // From
        $from = 'FROM ' . array_shift($tbl_list);

        // Join
        foreach($tbl_list as $name)
        {
            $from .= " LEFT JOIN $name USING (serial_number)";
        }
    
        // Paging
        $sLimit = sprintf(' LIMIT %d,%d', 
            $cfg['iDisplayStart'], $cfg['iDisplayLength']);

        // Show all
        if( $cfg['iDisplayLength'] == -1 )
        {
            $sLimit = '';
        }

        // Ordering
        $sOrder = "";
        if(count($cfg['sort_cols']) > 0)
        {
            $sOrder = "ORDER BY  ";
            $order_arr = array();
            foreach($cfg['sort_cols'] AS $pos => $direction)
            {
                $order_arr[] = sprintf('%s %s', $formatted_columns[$pos], $direction);
            }
            $sOrder = "ORDER BY  ".implode(',', $order_arr);
        }

        // Search
        $sWhere = "";
        if($cfg['sSearch'])
        {
            $sWhere = "WHERE (";
            foreach($formatted_columns AS $col)
            {
                $sWhere .= $col." LIKE '%".( $cfg['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }

        // Get filtered results count
        $iFilteredTotal = $iTotal;
        if( $sWhere)
        {
            $sql = "
                SELECT COUNT(*) as count
                $from
                $sWhere";
            if( ! $stmt = $dbh->prepare( $sql ))
            {
                $err = $dbh->errorInfo();
				die($err[2]);
            }
            $stmt->execute();// $bindings );
            if( $rs = $stmt->fetch( PDO::FETCH_OBJ ) )
            {
                $iFilteredTotal = $rs->count;
            }   
        }
        
        $output = array(
            "sEcho" => intval($cfg['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array(),
            "order" => $sOrder
        );


        $sql = "
        $select
        $from
        $sWhere
        $sOrder
        $sLimit
        ";
        //echo $sql;
        //return;
        
        if( ! $stmt = $dbh->prepare( $sql ))
        {
            $err = $dbh->errorInfo();
			die($err[2]);
        }
        $stmt->execute();// $bindings );
        $arr=array();
        while ( $rs = $stmt->fetch( PDO::FETCH_NUM ) )
        {
            $output['aaData'][] = array_combine($cfg['cols'], $rs);
        }        

        return $output;
        
    }

}
