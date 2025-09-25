@extends('layouts.app')
@php use Carbon\Carbon; @endphp

@section('content')
<div class="container text-center py-5">
    <div class="jumbotron bg-light p-5 rounded shadow-sm">
        <img src="/logo.png">
        <h1 class="display-4">Welcome to the Survey App!</h1>
        <!-- <p class="lead">Create, share, and analyze surveys with ease.</p> -->
        <hr class="my-4">
        <p>PSR Bharat is a group entity that operates under Politech India Innovative Pvt Ltd, focusing specifically on the domains of political research and survey services in India. As a subsidiary or affiliated unit, PSR Bharat leverages the parent company's innovative methodologies, resources, and digital strategies to deliver advanced political research solutions and campaign analytics</p>
        <a class="btn btn-primary btn-lg" href="{{ route('surveys.index') }}" role="button">View Surveys</a>
    </div>

    <div class="container py-5">
        <h2 class="text-center mb-4">Available Surveys</h2>
        <div class="row">
            @foreach ($surveys as $survey)
                @if ($survey->results_visibility === 'show' || ($survey->results_visibility === 'datetime' && $survey->results_visible_from && \Carbon\Carbon::now()->gte($survey->results_visible_from)))
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if ($survey->meta_image)
                            <img src="{{ asset('storage/' . $survey->meta_image) }}" class="card-img-top" alt="{{ $survey->title }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $survey->title }}</h5>
                            <p class="card-text">{{ \Illuminate\Support\Str::limit($survey->description, 100) }}</p>
                            <div class="mt-auto">
                                <!-- <a href="{{ route('public.surveys.take', $survey) }}" class="btn btn-primary btn-sm">Take Survey</a> -->
                                <a href="{{ route('surveys.results', $survey) }}" class="btn btn-info btn-sm text-white">View Results</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>

    @guest
        <div class="mt-4">
            <a class="btn btn-outline-primary" href="{{ route('login') }}">Login</a>
        </div>
    @endguest
</div>
@endsection
