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
        $dbh = getdbh();

        // Initial value
        $iTotal = 0;

        // Get tables from column names
        $tables = array();

        // Check if we add the machine table
        if($cfg['mrAddMachineTbl'])
        {
            $tables['machine'] = 1;
        }

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

        // Where not empty
        $where = '';
        if( $cfg['mrColNotEmpty'])
        {
            $tbl_col_array = explode('#', $cfg['mrColNotEmpty']);
            if(count($tbl_col_array) == 2)
            {
                // Format column name
                $where = sprintf('WHERE `%s`.`%s` IS NOT NULL', 
                    $tbl_col_array[0], $tbl_col_array[1]);
            }
            else
            {
                $where = sprintf('WHERE `%s` IS NOT NULL', $cfg['mrColNotEmpty']);
            }
        }

        // Business unit filter (assumes we are selecting the machine table)
        if(isset($_SESSION['machine_groups']))
        {
            // Todo: We should check if a requested machine_group is allowed

            $bu_where = 'machine.computer_group IN ('. implode(', ', $_SESSION['machine_groups']). ')';
            if($where)
            {
                $where .= ' AND (' . $bu_where . ')';
            }
            else
            {
                $where = 'WHERE (' .$bu_where . ')';
            }
        }

        // Get total records
        $sql = "
            SELECT COUNT(1) as count
            $from
            $where";
        if( ! $stmt = $dbh->prepare( $sql ))
        {
            $err = $dbh->errorInfo();
            die($err[2]);
        }
        $stmt->execute();// $bindings );
        if( $rs = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
            $iTotal = $rs->count;
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
        $sWhere = $where;
        if($cfg['sSearch'])
        {
            $sWhere = $where ? $where . " AND (" : "WHERE (";
            foreach($formatted_columns AS $col)
            {
                $sWhere .= $col." LIKE '%".( $cfg['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }

        // Search columns
        if($cfg['search_cols'])
        {
            $sWhere = $where ? $where . " AND (" : "WHERE (";
            foreach ($cfg['search_cols'] as $pos => $val)
            {
                if(is_string($val))
                {
                    if(preg_match('/([<>=] \d+)|BETWEEN\s+\d+\s+AND\s+\d+$/', $val))
                    {
                        // Special case, use unquoted
                        $compstr = $val;
                    }
                    else
                    {
                        // Regular string, quote
                        $compstr = " = '$val'";
                    }
                }
                else // Integer or boolean
                {
                    $compstr = " = $val";
                }
                $sWhere .= $formatted_columns[$pos].$compstr." OR ";
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
            "aaData" => array()
        );


        $sql = "
        $select
        $from
        $sWhere
        $sOrder
        $sLimit
        ";

        // When in debug mode, send sql as well
        if(conf('debug'))
        {
            $output['sql'] = str_replace("\n", '', $sql);
        }
        
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
