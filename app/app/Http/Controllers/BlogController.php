<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        return view(
            'blog', [
                'posts' => [
                    // TODO: use ActiveRecord
                    [
                        'author' => 'Jhony',
                        'timestamp' => '2018-09-21',
                        'body' => 'Hey, I\'m kinda new here, someone to help me out?',
                    ],
                    [
                        'author' => 'br3athein',
                        'timestamp' => '2018-09-21',
                        'body' => 'Well, hello there, Jhony',
                    ],
                ],
                // TODO: no paging here
                'bpdata' => BlogPost::all(),
            ]
        );
    }

    public function newPost(Request $request)
    {
        $newPost = new BlogPost;
        $newPost->body = $request->body;
        // TODO: resolve user_id
        $newPost->save();
        return view('blog');
    }
}
