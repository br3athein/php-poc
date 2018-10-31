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
                // TODO: no paging here
                'posts' => BlogPost::with(['author'])->get()->reverse(),
            ]
        );
    }

    public function store(Request $request)
    {
        $newPost = new BlogPost;
        $newPost->body = $request->body;
        // TODO: resolve user_id
        $newPost->user_id = \Auth::user()->id;
        $newPost->save();
        return $this->index();
    }
}
