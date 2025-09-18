@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-4">
                <h2 class="fw-bold">{{ $survey->title }}</h2>
                <p class="text-muted">Survey Results</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Your Response</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach ($survey->questions as $question)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $question->text }}</span>
                                @php
                                    $answer = $surveyResponse->answers->firstWhere('question_id', $question->id);
                                @endphp
                                <span class="badge bg-primary rounded-pill">
                                    @if ($answer)
                                        {{ $answer->option->text }}
                                    @else
                                        Not Answered
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Overall Results</h5>
                    <span class="badge bg-secondary rounded-pill">Total Responses: {{ $totalResponses }}</span>
                </div>
                <div class="card-body">
                    @foreach ($questionResults as $questionId => $data)
                        <div class="mb-4">
                            <h6 class="fw-bold">{{ $data['question']->text }}</h6>
                            @foreach ($data['options'] as $optionData)
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>
                                            @if ($optionData['option']->image_path)
                                                <img src="{{ asset('storage/' . $optionData['option']->image_path) }}" alt="Option Image" class="me-2" style="width: 24px; height: 24px; object-fit: cover;">
                                            @endif
                                            {{ $optionData['option']->text }}
                                        </span>
                                        <span class="text-muted small">{{ $optionData['count'] }} votes</span>
                                    </div>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $optionData['percentage'] }}%;" aria-valuenow="{{ $optionData['percentage'] }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ $optionData['percentage'] }}%
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if (!$loop->last)
                            <hr class="my-4">
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('public.surveys.take', $survey) }}" class="btn btn-outline-primary">Take Survey Again</a>
                <button onclick="copyToClipboard('{{ route('public.surveys.take', $survey) }}')" class="btn btn-outline-secondary">Share Survey</button>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Survey link copied to clipboard!');
    }, function(err) {
        alert('Could not copy text: ', err);
    });
}
</script>
@endsection