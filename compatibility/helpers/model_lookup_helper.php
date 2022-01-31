<?php
use Illuminate\Support\Facades\Http;

function machine_model_lookup($serial)
{
    // VMs have mixed case serials sometime
    if (strtoupper($serial) != $serial) {    
        return "Virtual Machine";
    }

    $options = [
        'query' => [
            'page' => 'categorydata',
            'serialnumber' => $serial
        ]
    ];

    try {
        $response = Http::get('https://km.support.apple.com/kb/index', $options);
        $categorydata = $response->json();
        if(isset($categorydata->name)){
            return $categorydata->name;
        }
        else{
            return 'unknown_model';
        }
    } catch (Exception $e) {
        return 'model_lookup_failed';
    }

}
