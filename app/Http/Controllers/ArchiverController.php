<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use \Reportdata_model;

class ArchiverController extends Controller
{
    public function update_status($serial_number = '')
    {
        // Check authorization
        if (!Str::contains(config('auth.methods'), 'NOAUTH')) {
            $this->middleware('auth');
            Gate::authorize('archive');
        }

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
        // Check authorization
        if (!Str::contains(config('auth.methods'), 'NOAUTH')) {
            $this->middleware('auth');
            Gate::authorize('archive');
        }

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
