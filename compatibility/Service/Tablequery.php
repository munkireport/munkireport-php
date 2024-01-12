<?php
namespace Compatibility\Service;

use \PDO;

class Tablequery
{
    // Clean unsafe strings
    public function dirify($string, $chars = '\w')
    {
        return preg_replace("/[^$chars]/", '', $string);
    }

    private function sanitize_column_name($string)
    {
        return preg_replace("/[^a-zA-Z0-9._]/", '', $string);
    }

    private function sanitize_sort_order($string)
    {
        return preg_replace("/[^a-zA-Z]/", '', $string);
    }

    // ------------------------------------------------------------------------

    /**
     * Retrieve all entries for serial
     *
     * @param array $cfg an array of query options for 'columns', 'search', etc.
     * @return array
     * @author abn290
     **/
    public function fetch($cfg)
    {

        // Quick debug
        $debug = false;

        $dbh = getdbh();

        // Initial value
        $recordsTotal = 0;

        // Output array
        $output = array(
            "draw" => intval($cfg['draw']),
            "data" => array()
        );

        // Get tables from column names
        $tables = array();

        // Add the reportdata table
        $tables['reportdata'] = 1;

        $formatted_columns = $columns = $search_cols = array();
        foreach ($cfg['columns'] as $pos => $column) {
            $column_name = $this->sanitize_column_name($column['name']);
            $tbl_col_array = explode('.', $column_name);
            if (count($tbl_col_array) == 2) {
            // Store table name
                $tables[$tbl_col_array[0]] = 1;
                // Format column name
                $formatted_columns[$pos] = sprintf(
                    '`%s`.`%s`',
                    $tbl_col_array[0],
                    $tbl_col_array[1]
                );
            } else {
                $formatted_columns[$pos] = sprintf('`%s`', $column_name);
            }
            $columns[$pos] = $column_name;

            // Check if search in column
            if (isset($column['search']['value']) && $column['search']['value']) {
                $search_cols[$pos] = $column['search']['value'];
            }
        }

        // Select
        $select = "SELECT ".implode(',', $formatted_columns);

        $tbl_list = array_keys($tables);

        // From
        $from = 'FROM ' . array_shift($tbl_list);

        // Join
        foreach ($tbl_list as $name) {
            $from .= " LEFT JOIN $name USING (serial_number)";
        }

        // Where not empty
        $where = '';
        if ($cfg['mrColNotEmpty']) {
            $where = sprintf('WHERE %s IS NOT NULL', $this->sanitize_column_name($cfg['mrColNotEmpty']));
        }

        // Where not empty or blank
        if ($cfg['mrColNotEmptyBlank']) {
            $where = sprintf("WHERE %s != ''", $this->sanitize_column_name($cfg['mrColNotEmptyBlank']));
        }

        $bindings = array();

        // Extra where clause (can only do is equal)
        if (is_array($cfg['where'])) {
            foreach ($cfg['where'] as $entry) {
                $operator = isset($entry['operator']) ? $entry['operator'] : '=';

                // Sanitize input
                $entry['table'] = $this->dirify($entry['table']);
                $entry['column'] = $this->dirify($entry['column']);
                $operator = $this->dirify($operator, '!=<>');
                $bindings[] = $entry['value'];

                $my_where = sprintf("%s.%s $operator ?", $entry['table'], $entry['column']);
                if ($where) {
                    $where .= ' AND (' . $my_where . ')';
                } else {
                    $where = 'WHERE (' .$my_where . ')';
                }
            }
        }

        // Business unit filter
        if ($where) {
            $where .= get_machine_group_filter('AND');
        } else {
            $where = get_machine_group_filter();
        }

        // Get total records
        $sql = "
            SELECT COUNT(1) as count
            $from
            $where";

        /** @phpstan-ignore-next-line */
        if ($debug) {
            print $sql;
        }

        if (! $stmt = $dbh->prepare($sql)) {
            $err = $dbh->errorInfo();
            die($err[2]);
        }
        $stmt->execute($bindings);// $bindings );
        if ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
            $recordsTotal = $rs->count;
        }

        // Paging
        $sLimit = sprintf(
            ' LIMIT %d,%d',
            filter_var($cfg['start'], FILTER_SANITIZE_NUMBER_INT),
            filter_var($cfg['length'], FILTER_SANITIZE_NUMBER_INT)
        );

        // Show all
        if ($cfg['length'] == -1) {
            $sLimit = '';
        }

        // Ordering
        $sOrder = "";
        if (count($cfg['order'])) {
            $sOrder = "ORDER BY  ";
            $order_arr = [];
            foreach ($cfg['order'] as $order_entry) {
                $order_arr[] = sprintf('%s %s', 
                    $this->sanitize_column_name($formatted_columns[$order_entry['column']]),
                    $this->sanitize_sort_order($order_entry['dir'])
                );
            }
            $sOrder = "ORDER BY  ".implode(',', $order_arr);
        }

        // Search
        // Search columns overrides global search
        $sWhere = $where;
        if ($search_cols) {
            $sWhere = $where ? $where . " AND (" : "WHERE (";
            foreach ($search_cols as $pos => $val) {
                if (preg_match('/([<>=] \d+)|BETWEEN\s+\d+\s+AND\s+\d+$/', $val)) {
                    // Special case, use unquoted
                    $compstr = $val;
                } elseif(preg_match('/[%_]+/', $val)) {
                    $bindings[] = $val;
                    $compstr = " LIKE ?";
                } else {
                    $bindings[] = $val;
                    $compstr = " = ?";
                }

                $sWhere .= $formatted_columns[$pos].$compstr." OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        } elseif ($cfg['search']) {
            $sWhere = $where ? $where . " AND (" : "WHERE (";
            foreach ($formatted_columns as $col) {
                $bindings[] = '%'.$cfg['search'].'%';
                $sWhere .= $col." LIKE ? OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }


        // Get filtered results count
        $recordsFiltered = $recordsTotal;
        if ($sWhere) {
            $sql = "
                SELECT COUNT(*) as count
                $from
                $sWhere";

            /** @phpstan-ignore-next-line */
            if ($debug) {
                echo "\nFiltered count: $sql";
                print_r($bindings);
            }

            if (! $stmt = $dbh->prepare($sql)) {
                $err = $dbh->errorInfo();
                die($err[2]);
            }
            $stmt->execute($bindings);//  );
            if ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                $recordsFiltered = $rs->count;
            }
        }

        $output["recordsTotal"] = $recordsTotal;
        $output["recordsFiltered"] = $recordsFiltered;


        $sql = "
        $select
        $from
        $sWhere
        $sOrder
        $sLimit
        ";

        /** @phpstan-ignore-next-line */
        if ($debug) {
            echo "\nFiltered: $sql";
        }

        // When in debug mode, send sql as well
        if (conf('debug')) {
            $output['sql'] = str_replace("\n", '', $sql);
        }

        if (! $stmt = $dbh->prepare($sql)) {
            $err = $dbh->errorInfo();
            die($err[2]);
        }
        $stmt->execute($bindings);// $bindings );
        $arr=array();
        while ($rs = $stmt->fetch(PDO::FETCH_NUM)) {
            $output['data'][] = $rs;
        }

        return $output;
    }
}
