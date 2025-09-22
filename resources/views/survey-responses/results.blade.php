@extends('layouts.public')

@push('meta')
    @php
        $metaDescription = $survey->meta_description ?? "See the survey results for '" . $survey->title . "'";
        $metaImage = null;

        if ($survey->meta_image) {
            $metaImage = asset('storage/' . $survey->meta_image);
        } else {
            foreach ($survey->questions as $question) {
                foreach ($question->options as $option) {
                    if ($option->image_path) {
                        $metaImage = asset('storage/' . $option->image_path);
                        break 2; // Breaks out of both loops
                    }
                }
            }
        }
    @endphp
    <meta property="og:title" content="{{ $survey->title }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('public.surveys.results', ['survey' => $survey, 'surveyResponse' => $surveyResponse]) }}">
    @if($metaImage)
    <meta property="og:image" content="{{ $metaImage }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $survey->title }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:url" content="{{ route('public.surveys.results', ['survey' => $survey, 'surveyResponse' => $surveyResponse]) }}">
@endpush

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
                <h5 class="fw-bold mb-3">Share these results:</h5>
                <div class="d-flex justify-content-center gap-2 flex-wrap share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('public.surveys.results', ['survey' => $survey, 'surveyResponse' => $surveyResponse])) }}" target="_blank" class="btn btn-primary mb-2 share-btn"><i class="fab fa-facebook"></i> Facebook</a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('public.surveys.results', ['survey' => $survey, 'surveyResponse' => $surveyResponse])) }}&text={{ urlencode($survey->title) }}" target="_blank" class="btn btn-info text-white mb-2 share-btn"><i class="fab fa-twitter"></i> Twitter</a>
                    <a href="https://wa.me/?text={{ urlencode($survey->title . ' - ' . route('public.surveys.results', ['survey' => $survey, 'surveyResponse' => $surveyResponse])) }}" target="_blank" class="btn btn-success mb-2 share-btn"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                    <button onclick="copyToClipboard('{{ route('public.surveys.results', ['survey' => $survey, 'surveyResponse' => $surveyResponse]) }}')" class="btn btn-outline-secondary mb-2 share-btn"><i class="fas fa-link"></i> Copy Link</button>
                </div>
                <a href="{{ route('public.surveys.take', $survey) }}" class="btn btn-outline-primary mt-3">Take Survey Again</a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.share-buttons .share-btn {
    transition: all 0.3s ease;
    border-radius: 50px;
    padding: 10px 20px;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.share-buttons .share-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.share-buttons .share-btn:active {
    transform: translateY(-1px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.1);
}

.share-buttons .fa-facebook { color: #fff; }
.share-buttons .fa-twitter { color: #fff; }
.share-buttons .fa-whatsapp { color: #fff; }
.share-buttons .fa-link { color: #6c757d; }

.share-buttons .btn-primary:hover .fa-facebook { color: white; }
.share-buttons .btn-info:hover .fa-twitter { color: white; }
.share-buttons .btn-success:hover .fa-whatsapp { color: white; }
.share-buttons .btn-outline-secondary:hover .fa-link { color: white; }

</style>
@endpush

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Survey link copied to clipboard!');
    }, function(err) {
        alert('Could not copy text: ', err);
    });
}
</script>
@endpush

@endsection