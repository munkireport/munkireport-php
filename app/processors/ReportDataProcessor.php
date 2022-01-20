<?php
namespace munkireport\processors;

use App\ReportData;
use CFPropertyList\CFPropertyList;
use App\Contracts\Processor as ProcessorContract;

class ReportDataProcessor extends Processor implements ProcessorContract
{
    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author abn290
     **/
    public function run(string $data): void
    {
        $parser = new CFPropertyList();
        $parser->parse($data, CFPropertyList::FORMAT_XML);
        $mylist = $parser->toArray();

        $model = ReportData::firstOrNew(['serial_number' => $this->serial_number]);

            // Check if reg_timestamp is set to determine this is a new client
        if ($model->reg_timestamp){
            $new_client = False;
        }else{
            $new_client = True;
            $mylist['reg_timestamp'] = time();
        }

        // Remove serial_number from mylist, use the cleaned serial that was provided in the constructor.
        $mylist['serial_number'] = $this->serial_number;

        // If console_user is empty, retain previous entry
        if (! $mylist['console_user']) {
            unset($mylist['console_user']);
        }

        // If long_username is empty, retain previous entry
        if (array_key_exists('long_username', $mylist) && empty($mylist['long_username'])) {
            unset($mylist['long_username']);
        }

        // If uid is empty, retain previous entry
        if (array_key_exists('uid', $mylist) && empty($mylist['uid'])) {
            unset($mylist['uid']);
        }

        $model->update($mylist);
        $model->save();    
        
        if ($new_client) {
            store_event($this->serial_number, 'reportdata', 'info', 'new_client');
        }
    }

}
