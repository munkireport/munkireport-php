<?php
namespace App\Http\Controllers;

use App\ReportData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ArchiverController extends Controller
{
    /**
     * Archive or Unarchive a single machine.
     *
     * @param Request $request
     * @param string $serial_number
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update_status(Request $request, string $serial_number): JsonResponse
    {
        $reportData = ReportData::where('serial_number', $serial_number)->firstOrFail();
        $this->authorize('archive', $reportData);

        if (!$request->has('status')) {
            return response()->json(['error' => 'No status found'])->setStatusCode(400);
        }
        $reportData->update([
            'archive_status' => intval($request->post('status')),
        ]);

        return response()->json(['updated' => intval($request->post('status'))]);
    }

    /**
     * Archive machines that have been inactive for a given number of days or greater.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function bulk_update_status(Request $request): JsonResponse
    {
        $this->authorize('archive_bulk', ReportData::class);

        if (!$request->has('days')) {
            return response()->json(['error' => 'No days sent'])->setStatusCode(400);
        }

        $days = intval($request->post('days'));

        $daysInterval = new \DateInterval("P{$days}D");
        $expireTimestamp = Carbon::now()->subtract($daysInterval)->unix();

        $changes = ReportData::where('timestamp', '<', $expireTimestamp)
            ->where('archive_status', 0)
            ->update(['archive_status' => 1]);

        return response()->json(['updated' => $changes]);
    }
}
