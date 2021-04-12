<?php
namespace App\Http\Controllers;

use App\Machine;
use Illuminate\Http\JsonResponse;
use MR\Kiss\View;

/**
 * Machine module class
 *
 * @package munkireport
 * @author
 **/
class MachineController extends Controller
{
    /**
     * Get duplicate computernames
     *
     *
     **/
    public function get_duplicate_computernames(): JsonResponse
    {
        $machine = Machine::selectRaw('computer_name, COUNT(*) AS count')
            ->filter()
            ->groupBy('computer_name')
            ->having('count', '>', 1)
            ->orderBy('count', 'desc')
            ->get()
            ->toArray();

        return response()->json($machine);
    }

    /**
     * Get model statistics
     *
     **/
    public function get_model_stats(string $summary = ""): JsonResponse
    {
        $machine = Machine::selectRaw('count(*) AS count, machine_desc AS label')
            ->filter()
            ->groupBy('machine_desc')
            ->orderBy('count', 'desc')
            ->get()
            ->toArray();

        $out = array();
        foreach ($machine as $obj) {
            $obj['label'] = $obj['label'] ? $obj['label'] : 'Unknown';
            $out[] = $obj;
        }

        // Check if we need to convert to summary (Model + screen size)
        if($summary){
            $model_list = array();
            foreach ($out as $key => $obj) {
                // Mac mini Server (Late 2012)
                //
                $suffix = "";
                if(preg_match('/^(.+) \((.+)\)/', $obj['label'], $matches))
                {
                    $name = $matches[1];
                    // Find suffix
                    if(preg_match('/([\d\.]+-inch)/', $matches[2], $matches))
                    {
                        $suffix = ' ('.$matches[1].')';
                    }
                }
                else
                {
                    $name = $obj['label'];

                }
                if(! isset($model_list[$name.$suffix]))
                {
                    $model_list[$name.$suffix] = 0;
                }
                $model_list[$name.$suffix] += $obj['count'];

            }
            // Erase out
            $out = array();
            // Sort model list
            arsort($model_list);
            // Add entries to $out
            foreach ($model_list as $key => $count)
            {
                $out[] = array('label' => $key, 'count' => $count);
            }
        }

        return response()->json($out);
    }


    /**
     * Get machine data for a particular machine
     **/
    public function report($serial_number = '')
    {
        jsonView(
            Machine::where('machine.serial_number', $serial_number)
                ->filter('groupOnly')
                ->first()
        );
    }

    /**
     * Return new clients
     **/
    public function new_clients(): JsonResponse
    {
        $lastweek = time() - 60 * 60 * 24 * 7;
        $out = Machine::query()->select('machine.serial_number', 'computer_name', 'reg_timestamp')
            ->where('reg_timestamp', '>', $lastweek)
            ->filter()
            ->orderBy('reg_timestamp', 'desc')
            ->get()
            ->toArray();

        return response()->json($out);
    }

    /**
     * Return json array with memory configuration breakdown
     *
     * @param string $format Format output. Possible values: flotr, none
     * @author AvB
     **/
    public function get_memory_stats(string $format = 'none'): JsonResponse
    {
        $out = array();

        // Legacy loop to do sort in php
        $tmp = array();
        $machine = Machine::selectRaw('physical_memory, count(1) as count')
            ->filter()
            ->groupBy('physical_memory')
            ->orderBy('physical_memory', 'desc')
            ->get()
            ->toArray();
        
        foreach ($machine as $obj) {
        // Take care of mixed entries (string or int)
            if (isset($tmp[$obj['physical_memory']])) {
                $tmp[$obj['physical_memory']] += $obj['count'];
            } else {
                $tmp[$obj['physical_memory']] = $obj['count'];
            }
        }

        switch ($format) {
            case 'flotr':
                krsort($tmp);

                $cnt = 0;
                foreach ($tmp as $mem => $memcnt) {
                    $out[] = array('label' => $mem . ' GB', 'data' => array(array(intval($memcnt), $cnt++)));
                }
                break;
            
            case 'button':
                $labels = ['< 4GB' => 0, '4GB +' => 0, '8GB +' => 0];
                foreach ($tmp as $mem => $memcnt) {
                    $memcnt = intval($memcnt);
                    if( $mem < 4 ){ $labels['< 4GB'] += $memcnt;}
                    if( $mem < 8 && $mem <= 4 ){ $labels['4GB +'] += $memcnt;}
                    if( $mem >= 8 ){ $labels['8GB +'] += $memcnt;}
                }

                foreach ($labels as $label => $count) {
                    $out[] = ['label' => $label, 'count' => $count]; 
                }
                break;

            default:
                foreach ($tmp as $mem => $memcnt) {
                    $out[] = array('label' => $mem, 'count' => intval($memcnt));
                }
        }

        return response()->json($out);
    }

    /**
     * Return json array with hardware configuration breakdown
     *
     * @author AvB
     **/
    public function hw(): JsonResponse
    {
        $out = [];
        $machine = Machine::selectRaw('machine_name, count(1) as count')
            ->filter()
            ->groupBy('machine_name')
            ->orderBy('count', 'desc')
            ->get()
            ->toArray();
        foreach ($machine as $obj) {
            $out[] = array('label' => $obj['machine_name'], 'count' => intval($obj['count']));
        }

        return response()->json($out);
    }

    /**
     * Return json array with os breakdown
     *
     * @author AvB
     **/
    public function os(): JsonResponse
    {
        return response()->json($this->_trait_stats('os_version'));
    }
    /**
     * Return json array with os build breakdown
     *
     * @author AkB
     **/
    public function osbuild(): JsonResponse
    {
        return response()->json($this->_trait_stats('buildversion'));
    }

    private function _trait_stats($what = 'os_version'): array {
        $out = [];
        $machine = Machine::selectRaw("count(1) as count, $what")
            ->filter()
            ->groupBy($what)
            ->orderBy($what, 'desc')
            ->get()
            ->toArray();

        foreach ($machine as $obj) {
            $obj[$what] = $obj[$what] ? $obj[$what] : '0';
            $out[] = ['label' => $obj[$what], 'count' => intval($obj['count'])];
        }
        return $out;
    }

    /**
     * Run machine lookup at Apple
     *
     **/
    public function model_lookup(string $serial_number): JsonResponse
    {
        require_once(__DIR__ . '/../../helpers/model_lookup_helper.php');
        $out = ['error' => '', 'model' => ''];
        try {
            $machine = Machine::select()
                ->where('serial_number', $serial_number)
                ->firstOrFail();
            $machine->machine_desc = machine_model_lookup($serial_number);
            $machine->save();
            $out['model'] = $machine->machine_desc;
        } catch (\Throwable $th) {
            // Record does not exist
            $out['error'] = 'lookup_failed';
        }

        return response()->json($out);
    }
} // END class Machine_controller
