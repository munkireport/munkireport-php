<?php
namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use \Parsedown;

/**
 * Comment_controller class
 *
 * @package munkireport
 * @author AvB
 **/
class CommentController extends Controller
{
    /**
     * Create a comment
     *
     **/
    public function save(Request $request)
    {
        $out = array();

        // Sanitize
        $serial_number = $request->post('serial_number');
        $section = $request->post('section');
        $text = $request->post('text');
        $html = $this->ParseMarkdown($text);

        if ($serial_number and $section and $text) {
            if (authorized_for_serial($serial_number)) {
                $comment = Comment::updateOrCreate(
                    [
                        'serial_number' => $serial_number,
                        'section' => $section,
                    ],
                    [
                        'text' => $text,
                        'html' => $html,
                        'user' => $_SESSION['user'],
                        'timestamp' => time(),
                    ]
                );
                $out['status'] = 'saved';
            } else {
                $out['status'] = 'error';
                $out['msg'] = 'Not authorized for this serial';
            }
        } else {
            $out['status'] = 'error';
            $out['msg'] = 'Missing data';
        }

        return view('json', ['msg' => $out]);
    }

    /**
     * Retrieve data in json format
     *
     **/
    public function retrieve($serial_number = '', $section = '')
    {
        $out = [];
        $where[] = ['comment.serial_number', $serial_number];
        if($section){
            $where[] = ['section', $section];
            $comment = Comment::where($where)
                ->filter('groupOnly')
                ->first();
            if ($comment) {
                $out = $comment;
            }
        }else {
            $comment = Comment::where($where)
                ->filter('groupOnly')
                ->get();
            if($comment){
               $out = $comment->toArray();
            }
        }

        return view('json', ['msg' => $out]);
    }

    /**
     * Update comment
     *
     **/
    public function update()
    {
    }

    /**
     * Delete comment
     *
     **/
    public function delete()
    {
    }

    private function ParseMarkdown($markdown)
    {
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
        return $parsedown->text($markdown);
    }

}
