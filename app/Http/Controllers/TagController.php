<?php
namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

/**
 * Tag_controller class
 *
 * @package munkireport
 * @author AvB
 **/
class TagController extends Controller
{
    
    public function listing()
    {
        return view('tag.tag_listing', []);
    }
    

    /**
     * Create a Tag
     *
     **/
    public function save(Request $request)
    {

        $out = array();

        // Sanitize
        $serial_number = $request->post('serial_number');
        $tag = $request->post('tag');
        if ($serial_number and $tag) {
            if (authorized_for_serial($serial_number)) {
                $tag = Tag::updateOrCreate(
                    [
                        'serial_number' => $serial_number,
                        'tag' => $tag,
                    ],
                    [
                        'user' => $request->user()->name,
                        'timestamp' => time(),
                    ]
                );

                $out = $tag;
            } else {
                $out['status'] = 'error';
                $out['msg'] = 'Not authorized for this serial';
            }
        } else {
            $out['status'] = 'error';
            $out['msg'] = 'Missing data';
        }

        return view('json', ['msg' => $out]);
//        $obj = new View();
//        $obj->view('json', array('msg' => $out));
    }

    /**
     * Retrieve data in json format
     *
     **/
    public function retrieve($serial_number = '')
    {
        if (authorized_for_serial($serial_number)) {
            $Tag = Tag::where('serial_number', $serial_number)
                ->get()
                ->toArray();
        }
        else{
            $Tag = ['not authorized for this serial_number'];
        }

        return view('json', ['msg' => $Tag]);
//        $obj = new View();
//        $obj->view('json', array('msg' => $Tag));
    }

    /**
     * Delete Tag
     *
     **/
    public function delete($serial_number = '', $id = -1)
    {
        $out = [];
        $where = [];

        if (authorized_for_serial($serial_number)) {
            $where[] = ['serial_number', $serial_number];
            if($id){
                $where[] = ['id', $id];
            }
            print_r($where);
            echo Tag::where($where)
                ->delete();
            $out['status'] = 'success';
        }else{
            $out['status'] = 'error';
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
    
    /**
     * Get all defined tags
     *
     * Returns a JSON array with all defined tags, used for typeahead
     *
     **/
    public function all_tags($add_count = false)
    {
        // TODO: add ->filter() which applies machine group / bu filtering
        $Tag = Tag::selectRaw('tag, count(*) as cnt')
//            ->filter()
            ->groupBy('tag')
            ->orderBy('tag', 'asc');

        
            if ($add_count) {
                $out = $Tag->get()->toArray();
            } else {
                $out = $Tag->get()->pluck('tag')->toArray();
            }

            return view('json', ['msg' => $out]);
//        $obj = new View();
//        $obj->view('json', ['msg' => $out]);
    }
}
