<?php

namespace munkireport\controller;

use \Controller, \View, \Model, \Exception, \Reportdata_model;

class Archiver extends Controller
{
    public function __construct()
    {
        // Check authorization
        $this->authorized() || jsonError('Authenticate first', 403);
        $this->authorized('archive') || jsonError('You need to be archiver, manager or admin', 403);

        // Connect to database
        $this->connectDB();
    }


    //===============================================================

    public function index()
    {
        echo 'Archiver';
    }

    //===============================================================

    public function update_status($serial_number = '')
    {
        if (! isset($_POST['status'])) {
            jsonError('No status found');
        }
        $changes = Reportdata_model::where('serial_number', $serial_number)
                ->update(
                    [
                        'archive_status' => intval($_POST['status']),
                    ]
                );
        jsonView(['updated' => intval($_POST['status'])]);
    }

    public function bulk_update_status()
    {
        if( ! $days = intval(post('days'))){
            jsonError('No days sent');
        }
        $expire_timestamp = time() - ($days * 24 * 60 * 60);
        $changes = Reportdata_model::where('timestamp', '<', $expire_timestamp)
                ->where('archive_status', 0)
                ->update(['archive_status' => 1]);
        jsonView(['updated' => $changes]);
    }
}
