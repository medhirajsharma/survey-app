@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Question') }}</div>

                <div class="card-body">
                    <form action="{{ route('questions.update', $question) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="text">Question</label>
                            <input type="text" name="text" id="text" class="form-control" value="{{ $question->text }}" required>
                        </div>

                        <div class="form-group">
                            <label>Options</label>
                            @foreach ($question->options as $key => $option)
                                <input type="text" name="options[{{ $key }}][text]" class="form-control mb-2" value="{{ $option->text }}" required>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary">Update Question</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
