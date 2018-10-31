@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="card-header">Local blog</div>

                    <div class="card-body">
                        <p>What's on your mind?</p>
                        <form method="put" action="blog">
                            <input type="hidden" name="action" value="blog"/>
                            <input type="text" name="body"/>
                            <br>
                            <input type="submit" value="Post"/>
                        </form>
                    </div>
                </div>
            </div>
            @foreach ($posts as $post)
                <div class="card">
                    <div class="card-header">
                        {{ $post->author }} posted at {{ $post->timestamp }}
                    </div>

                    <div>
                        {{ $post->body }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
