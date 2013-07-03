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

        // From
        $from = 'FROM ' . implode(',', array_keys($tables));

        // Where
        if(count($tables) > 1)
        {
            $where_arr = array();
            foreach($tables as $name => $val)
            {
                if($name != 'machine')
                {
                    $where_arr[] = "`$name`.`serial_number` = `machine`.`serial_number`";
                }

            }
            $sWhere = 'WHERE ' . implode(' AND ', $where_arr);
        }
        else
        {
            $sWhere = "";
        }

        // Paging
        $sLimit = sprintf(' LIMIT %d,%d', 
            $cfg['iDisplayStart'], $cfg['iDisplayLength']);

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
       // if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
       // {
       //     $sWhere = "(";
       //     for ( $i=0 ; $i<count($aColumns) ; $i++ )
       //     {
       //         $sWhere .= "`".$aColumns[$i]."` LIKE '%".( $_GET['sSearch'] )."%' OR ";
       //     }
       //     $sWhere = substr_replace( $sWhere, "", -3 );
       //     $sWhere .= ')';
       // }

        // Get filtered results count
        //if( $sWhere)
        //{
        //    $iFilteredTotal = $ref_obj->count($sWhere);;
        //}
        //else
        //{
        //    $iFilteredTotal = $iTotal;
        //}
        $iFilteredTotal = $iTotal;
        
        
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
        $dbh = getdbh();
        $stmt = $dbh->prepare( $sql );
        $stmt->execute();// $bindings );
        $arr=array();
        while ( $rs = $stmt->fetch( PDO::FETCH_NUM ) )
        {
            
            $output['aaData'][] = array_combine($cfg['cols'], $rs);
        }        

        return $output;
        
    }

}
