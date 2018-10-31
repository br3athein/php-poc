@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Local blog') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>{{ __('What\'s on your mind?') }}</p>

                    <form method="post" action="{{ route('blogposts.store') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-10">
                                <input type="text" name="body" value="{{ old('body') }}"
                                    class="form-control {{ $errors->has('body') ? ' is-invalid' : '' }}"
                                    placeholder="{{ __('Wassup?') }}"/>

                                @if ($errors->has('body'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Post!') }}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <hr class="col-md-6">
    @if (count($blogPosts) < 1)
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    {{ __('No blog posts are here yet.') }}
                </div>
            </div>
        </div>
    </div>
    @endif
    @foreach ($blogPosts as $post)
    <div class="row justify-content-center my-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <b>{{ $post->author->name }}</b> {{ __('posted at') }} {{ $post->created_at }}
                </div>

                <div class="card-body">
                    {{ $post->body }}
                </div>
            </div>
        </div>
    </div>
    @endforeach

</div>
@endsection
