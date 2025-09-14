@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4>{{ __('Surveys') }}</h4>
                    <a href="{{ route('surveys.create') }}" class="btn btn-light">Create Survey</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($surveys as $survey)
                                    <tr>
                                        <td>{{ $survey->title }}</td>
                                        <td>{{ $survey->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('surveys.show', $survey) }}" class="btn btn-sm btn-info me-1">View</a>
                                            <a href="{{ route('surveys.edit', $survey) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                                            <a href="{{ route('surveys.report', $survey) }}" class="btn btn-sm btn-secondary me-1">Report</a>
                                            <button class="btn btn-sm btn-success me-1" onclick="copyToClipboard('{{ route('public.surveys.take', $survey) }}')">Copy Public URL</button>
                                            <form action="{{ route('surveys.destroy', $survey) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this survey?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No surveys found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $surveys->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Public URL copied to clipboard!');
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection