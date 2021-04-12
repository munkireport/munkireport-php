<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use munkireport\models\Reportdata_model;

class ArchiverController extends Controller
{
    public function update_status($serial_number = '')
    {
        $reportData = Reportdata_model::where('serial_number', $serial_number)->firstOrFail();
        $this->authorize('archive', $reportData);

        if (! isset($_POST['status'])) {
            jsonError('No status found');
        }
        $reportData->update([
            'archive_status' => intval($_POST['status']),
        ]);
        jsonView(['updated' => intval($_POST['status'])]);
    }

    public function bulk_update_status()
    {
        $this->authorize('archive_bulk', \Reportdata_model::class);

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
