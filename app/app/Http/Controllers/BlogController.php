<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
                        'body' => 'Hey, I\'n kinda new here',
                    ],
                    [
                        'author' => 'br3athein',
                        'timestamp' => '2018-09-21',
                        'body' => 'Well, hello there , Jhony',
                    ],
                ],
            ]
        );
    }
}
