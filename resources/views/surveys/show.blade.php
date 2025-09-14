@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $survey->title }}</div>

                <div class="card-body">
                    <p>{{ $survey->description }}</p>

                    <a href="{{ route('surveys.questions.create', $survey) }}" class="btn btn-primary mb-3">Add Question</a>

                    <h5>Questions</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($survey->questions as $question)
                                <tr>
                                    <td>{{ $question->text }}</td>
                                    <td>
                                        <a href="{{ route('questions.edit', $question) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('questions.destroy', $question) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
