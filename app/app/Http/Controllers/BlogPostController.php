<?php

namespace App\Http\Controllers;

use App\BlogPost;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateBlogPostStore;

class BlogPostController extends Controller
{
    public function index()
    {
        return view(
            'blog', [
                'blogPosts' => BlogPost::with(['author'])
                    ->latest()
                    ->get(),
            ]
        );
    }

    public function store(ValidateBlogPostStore $request)
    {
        $blogPost = new BlogPost;
        $blogPost->fill($request->all());
        $blogPost->user_id = \Auth::user()->id;
        $blogPost->save();

        return redirect()
            ->route('blogposts.index')
            ->with('status', __('Blog Post created!'));
    }
}
