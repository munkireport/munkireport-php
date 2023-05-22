<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Machine;
use App\ReportData;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Free-text search a specific Eloquent Model (using Laravel Scout).
     *
     * The model must use the Searchable trait to be searchable, and the model must
     * already be indexed (via CLI or via API).
     *
     * @param string $model
     * @param string $query
     */
    public function searchModel(string $model, string $query, int $limit = 10) {
        $searchResults = [];

        // This seemingly insane switch block is to prevent arbitrary access to models with passwords
        switch (strtolower($model)) {
            case 'machine':
                $searchResults = Machine::search($query)->get()->take($limit);
                break;
            case 'reportdata':
                $searchResults = ReportData::search($query)->get()->take($limit);
                break;
            default:
                abort(404, "No valid model name specified for search query");
        }

        return $searchResults;
    }
}
