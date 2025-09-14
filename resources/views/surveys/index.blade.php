@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Surveys') }}</div>

                <div class="card-body">
                    <a href="{{ route('surveys.create') }}" class="btn btn-primary mb-3">Create Survey</a>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($surveys as $survey)
    <tr>
        <td>{{ $survey->title }}</td>
        <td>
            <a href="{{ route('surveys.show', $survey) }}" class="btn btn-sm btn-info">View</a>
            <a href="{{ route('surveys.edit', $survey) }}" class="btn btn-sm btn-primary">Edit</a>
            <form action="{{ route('surveys.destroy', $survey) }}" method="POST" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
            <a href="{{ route('surveys.report', $survey) }}" class="btn btn-sm btn-secondary">Report</a>
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
