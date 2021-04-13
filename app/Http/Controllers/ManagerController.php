<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ManagerController extends Controller
{
    public function delete_machine(string $serial_number = '')
    {
        Gate::authorize('delete_machine');

        $status = array('status' => 'undefined', 'rowcount' => 0);

        // Delete machine entry from all tables
        $machine = new \MR\Kiss\Model;

        // List tables (unfortunately this is not db-agnostic)
        switch ($machine->get_driver()) {
            case 'sqlite':
                $tbl_query = "SELECT name FROM sqlite_master
                    WHERE type = 'table' AND name NOT LIKE 'sqlite_%'";
                break;
            default:
                // Get database name from dsn string
                $dbname = isset(conf('connection')['database']) ? conf('connection')['database'] : '';
                if ($dbname) {
                    $tbl_query = "SELECT TABLE_NAME AS name FROM information_schema.TABLES
                    WHERE TABLE_TYPE='BASE TABLE' AND TABLE_SCHEMA='".$dbname."'";
                } else {
                    die('Admin:delete_machine: Cannot find database name.');
                }
        }

        // Get tables
        $tables = array();
        foreach ($machine->query($tbl_query) as $obj) {
            if($this->isTableNameOk($obj->name)){
                $tables[] = $obj->name;
            }
        }

        // Get database handle
        $dbh = getdbh();
        $dbh->beginTransaction();

        // Affected rows counter
        $cnt = 0;

        try {
            // Delete entries
            foreach ($tables as $table) {

                $sql = "DELETE FROM $table WHERE `serial_number`=?";
                if (! $stmt = $dbh->prepare($sql)) {
                    die('Prepare '.$sql.' failed');
                }
                $stmt->bindValue(1, $serial_number);
                $stmt->execute();
                $cnt += $stmt->rowCount();
            }

            $dbh->commit();

            // Return status
            $status['status'] = 'success';
            $status['rowcount'] = $cnt;
        } catch (Exception $e) {
            $status['status'] = 'error';
            $status['message'] = sprintf('Delete failed for table %s: %s', $table, $e->getMessage());
        }

        return response()->json($status);
    }

    private function isTableNameOk($name)
    {
        $skip_tables = [
            'migration',
            'migrations',
            'business_unit',
            'machine_group',
            'cache',
        ];

        // Check if old table
        if(preg_match('/_orig$/', $name)){
            return false;
        }

        // Check if in skip tables
        if (in_array($name, $skip_tables)) {
            return false;
        }

        return true;
    }
}
