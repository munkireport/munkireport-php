<?php
namespace App\Http\Controllers;

use App\Event;
use Symfony\Component\Yaml\Yaml;

/**
 * Event module class
 *
 * Removed in v6: event filter configuration via .yaml file.
 *
 * @package munkireport
 * @author AvB
 **/
class EventController extends Controller
{
    /**
     * Get Event
     *
     * @author AvB
     **/
    public function get($limit = 0)
    {

        $queryobj = Event::with('machine')
            ->orderBy('event.timestamp', 'desc')
            ->limit($limit);

        return view('json', [
            'msg' => [
                'error' => '',
                'items' => $queryobj->get()->toArray(),
            ]
        ]);
    }
}
