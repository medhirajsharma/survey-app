@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <div class="jumbotron bg-light p-5 rounded shadow-sm">
        <h1 class="display-4">Welcome to the Survey App!</h1>
        <p class="lead">Create, share, and analyze surveys with ease.</p>
        <hr class="my-4">
        <p>Explore existing surveys or create your own to gather valuable feedback.</p>
        <a class="btn btn-primary btn-lg" href="{{ route('surveys.index') }}" role="button">View Surveys</a>
    </div>

    @guest
        <div class="mt-4">
            <a class="btn btn-outline-primary" href="{{ route('login') }}">Login</a>
        </div>
    @endguest
</div>
@endsection
