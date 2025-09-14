@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $survey->title }} - Report</div>

                <div class="card-body">
                    <p>{{ $survey->description }}</p>

                    <hr>

                    @foreach ($survey->questions as $question)
                        <div class="mb-4">
                            <h5>{{ $question->text }}</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Option</th>
                                        <th>Votes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalVotes = $question->options->sum(function ($option) {
                                            return $option->answers->count();
                                        });
                                    @endphp
                                    @foreach ($question->options as $option)
                                        <tr>
                                            <td>{{ $option->text }}</td>
                                            <td>
                                                <div class="progress">
                                                    @php
                                                        $percentage = $totalVotes > 0 ? round(($option->answers->count() / $totalVotes) * 100) : 0;
                                                    @endphp
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                                        {{ $option->answers->count() }} ({{ $percentage }}%)
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach

                    <hr>

                    <h5>Respondents</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Respondent Name</th>
                                @foreach ($survey->questions as $question)
                                    <th>{{ $question->text }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($surveyResponses as $response) {{-- Use $surveyResponses directly --}}
                                <tr>
                                    <td>{{ $response->name }}</td>
                                    @foreach ($survey->questions as $question)
                                        <td>
                                            @php
                                                $answer = $response->answers->firstWhere('option.question_id', $question->id);
                                            @endphp
                                            {{ $answer ? $answer->option->text : 'N/A' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $surveyResponses->links() }}
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('public.surveys.take', $survey) }}" class="btn btn-primary">Take Survey</a>
                        <button onclick="copyToClipboard('{{ route('public.surveys.take', $survey) }}')" class="btn btn-secondary">Share Survey</button>
                        <a href="{{ route('survey-reports.export', $survey) }}" class="btn btn-success">Export to CSV</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    var dummy = document.createElement("textarea");
    document.body.appendChild(dummy);
    dummy.value = text;
    dummy.select();
    document.execCommand("copy");
    document.body.removeChild(dummy);
    alert("Survey link copied to clipboard!");
}
</script>
@endsection
