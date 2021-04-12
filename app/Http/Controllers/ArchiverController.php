<?php
namespace App\Http\Controllers;

use App\ReportData;

class ArchiverController extends Controller
{
    public function update_status($serial_number = '')
    {
        $reportData = ReportData::where('serial_number', $serial_number)->firstOrFail();
        $this->authorize('archive', $reportData);

        if (! isset($_POST['status'])) {
            jsonError('No status found');
        }
        $reportData->update([
            'archive_status' => intval($_POST['status']),
        ]);

        return response()->json(['updated' => intval($_POST['status'])]);
    }

    public function bulk_update_status()
    {
        $this->authorize('archive_bulk', ReportData::class);

        if( ! $days = intval(post('days'))){
            jsonError('No days sent');
        }
        $expire_timestamp = time() - ($days * 24 * 60 * 60);
        $changes = ReportData::where('timestamp', '<', $expire_timestamp)
            ->where('archive_status', 0)
            ->update(['archive_status' => 1]);

        return response()->json(['updated' => $changes]);
    }
}
