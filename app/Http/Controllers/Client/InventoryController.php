<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use function xKerman\Restricted\unserialize;
use xKerman\Restricted\UnserializeFailedException;

class InventoryController extends Controller
{
    /**
     * Process MunkiReport Checkins using the \App\Reports class.
     *
     * The \App\Reports combines registration via Laravel ServiceProviders and falls back to the conventional
     * module manager instance to search for model_processor and module_model classes.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function check_in(Request $request)
    {
        $validatedData = $request->validate([
            'serial' => 'required', // |filter:FILTER_SANITIZE_STRING
            'items' => 'required|array',
        ]);

        $reports = app(\App\Reports::class);
        try{
            $arr = unserialize($request->get('items'));
        }
        catch (Exception $e){
            Log::error($e);
            return response(serialize(array('error' => 'Could not unserialize items')));
        }

        if (! is_array($arr)) {
            return response(serialize(array('error' => 'Could not parse items, not a proper serialized file')));
        }

        $messages = $reports->processAll($request->input('serial'), $arr);

        return response(serialize($messages));
    }
}
