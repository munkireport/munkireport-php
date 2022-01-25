<?php
namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
                        'user' => Auth::user()->name,
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
     * @todo ->filter('groupOnly') is not working
     * @param string $serial_number
     * @param string|null $section The page or section where the comment will be displayed, eg. client (for client details)
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View|void
     */
    public function retrieve(string $serial_number, ?string $section = null)
    {
        $out = [];
        $where[] = ['comment.serial_number', $serial_number];
        if($section){
            $where[] = ['section', $section];
            $comment = Comment::where($where)
//                ->filter('groupOnly')
                ->first();
            if ($comment) {
                $out = $comment;
            }
        }else {
            $comment = Comment::where($where)
//                ->filter('groupOnly')
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
        $parsedown = new \Parsedown();
        $parsedown->setSafeMode(true);
        return $parsedown->text($markdown);
    }

}
