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
    public function searchModel(string $model, string $query) {
        $searchResults = [];

        // This seemingly insane switch block is to prevent arbitrary access to models with passwords
        switch ($model) {
            case 'Machine':
                $searchResults = Machine::search($query)->get();
                break;
            case 'ReportData':
                $searchResults = ReportData::search($query)->get();
                break;
        }

        return $searchResults;
    }
}
