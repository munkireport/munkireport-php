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
        $recordsTotal = 0;

        // Get tables from column names
        $tables = array();

        // Add the reportdata table
        $tables['reportdata'] = 1;

        $formatted_columns = $columns = $search_cols = array();
        foreach($cfg['columns'] AS $pos => $column)
        {
            $tbl_col_array = explode('.', $column['name']);
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
                $formatted_columns[$pos] = sprintf('`%s`', $column['name']);
            }
            $columns[$pos] = $column['name'];
            
            // Check if search in column
            if(isset($column['search']['value']) && $column['search']['value'])
            {
                $search_cols[$pos] = $column['search']['value'];
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
            $where = sprintf('WHERE %s IS NOT NULL', $cfg['mrColNotEmpty']);
        }

        // Business unit filter (assumes we are selecting the reportdata table)
        if($machine_groups = get_filtered_groups())
        {
            // Todo: We should check if a requested machine_group is allowed

            $bu_where = 'reportdata.machine_group IN ('. implode(', ', $machine_groups). ')';
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
        //print $sql;return;
        if( ! $stmt = $dbh->prepare( $sql ))
        {
            $err = $dbh->errorInfo();
            die($err[2]);
        }
        $stmt->execute();// $bindings );
        if( $rs = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
            $recordsTotal = $rs->count;
        }   

        // Paging
        $sLimit = sprintf(' LIMIT %d,%d', 
            $cfg['start'], $cfg['length']);

        // Show all
        if( $cfg['length'] == -1 )
        {
            $sLimit = '';
        }

        // Ordering
        $sOrder = "";
        if(count($cfg['order']))
        {
            $sOrder = "ORDER BY  ";
            $order_arr = array();
            foreach($cfg['order'] AS $order_entry)
            {
                $order_arr[] = sprintf('%s %s', $formatted_columns[$order_entry['column']], $order_entry['dir']);
            }
            $sOrder = "ORDER BY  ".implode(',', $order_arr);
        }

        // Search
        $sWhere = $where;
        if($cfg['search'])
        {
            $sWhere = $where ? $where . " AND (" : "WHERE (";
            foreach($formatted_columns AS $col)
            {
                $sWhere .= $col." LIKE '%".( $cfg['search'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }

        // Search columns
        if($search_cols)
        {
            $sWhere = $where ? $where . " AND (" : "WHERE (";
            foreach ($search_cols as $pos => $val)
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
        $recordsFiltered = $recordsTotal;
        if( $sWhere)
        {
            $sql = "
                SELECT COUNT(*) as count
                $from
                $sWhere";
            //print $sql;return;
            if( ! $stmt = $dbh->prepare( $sql ))
            {
                $err = $dbh->errorInfo();
				die($err[2]);
            }
            $stmt->execute();// $bindings );
            if( $rs = $stmt->fetch( PDO::FETCH_OBJ ) )
            {
                $recordsFiltered = $rs->count;
            }   
        }
        
        $output = array(
            "draw" => intval($cfg['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => array()
        );


        $sql = "
        $select
        $from
        $sWhere
        $sOrder
        $sLimit
        ";
        //print $sql;return;
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
            $output['data'][] = $rs;
        }        

        return $output;
        
    }

}
