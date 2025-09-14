@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $survey->title }}</div>

                <div class="card-body">
                    <p>{{ $survey->description }}</p>

                    <form action="{{ route('surveys.take', $survey) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <hr>

                        @foreach ($survey->questions as $key => $question)
                            <div class="mb-4">
                                <h5>{{ $question->text }}</h5>

                                @foreach ($question->options as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="option-{{ $option->id }}" value="{{ $option->id }}" required>
                                        <label class="form-check-label" for="option-{{ $option->id }}">
                                            {{ $option->text }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
