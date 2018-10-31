@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="card-header">Local blog</div>

                    <div class="card-body">
                        <!-- TODO: make it pretty, looks quite disgusting ATM -->

                        <p>What's on your mind?</p>
                        <form method="post" action="blog">
                            @method('PUT')
                            @csrf
                            <input type="text" name="body"/>
                            <br>
                            <input type="submit" value="Post!"/>
                        </form>
                    </div>
                </div>
            </div>

            <hr>
            @if (empty($posts))
                No blog posts are here yet.
            @endif
            @foreach ($posts as $post)
                <!-- TODO: use ActiveRecord -->
                <div class="card">
                    <div class="card-header">
                        {{ $post->author->name }} posted at {{ $post->created_at }}
                    </div>

                    <div class="card-body">
                        {{ $post->body }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
