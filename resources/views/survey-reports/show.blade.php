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
                                            <td>
                                                @if ($option->image_path)
                                                    <img src="{{ asset('storage/' . $option->image_path) }}" alt="Option Image" width="50" class="ml-2">
                                                @endif
                                                 {{ $option->text }}
                                            </td>
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

                    <form method="GET" action="{{ route('surveys.report', $survey) }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ request('name') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="mobile_no" class="form-control" placeholder="Mobile No" value="{{ request('mobile_no') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="vidhansabha_id" class="form-control">
                                    <option value="">All Vidhansabhas</option>
                                    @foreach ($vidhansabhas as $vidhansabha)
                                        <option value="{{ $vidhansabha->id }}" {{ request('vidhansabha_id') == $vidhansabha->id ? 'selected' : '' }}>
                                            {{ $vidhansabha->constituency_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('surveys.report', $survey) }}" class="btn btn-secondary">Clear</a>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><a href="{{ route('surveys.report', ['survey' => $survey, 'sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">Respondent Name</a></th>
                                <th><a href="{{ route('surveys.report', ['survey' => $survey, 'sort' => 'mobile_no', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">Mobile No</a></th>
                                <th><a href="{{ route('surveys.report', ['survey' => $survey, 'sort' => 'vidhansabha_id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">Vidhansabha</a></th>
                                <th>Caste</th>
                                @foreach ($survey->questions as $question)
                                    <th>{{ $question->text }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($surveyResponses as $response) {{-- Use $surveyResponses directly --}}
                                <tr>
                                    <td>{{ $response->name }}</td>
                                    <td>{{ $response->mobile_no }}</td>
                                    <td>{{ $response->vidhansabha->constituency_name ?? 'N/A' }}</td>
                                    <td>{{ $response->caste ?? 'N/A' }}</td>
                                    @foreach ($survey->questions as $question)
                                        <td>
                                            @php
                                                $answer = $response->answers->firstWhere('option.question_id', $question->id);
                                            @endphp
                                            @if ($answer)

                                                @if ($answer->option->image_path)
                                                    <img src="{{ asset('storage/' . $answer->option->image_path) }}" alt="Option Image" width="20" class="ml-2">
                                                @endif
                                                {{ $answer->option->text }}
                                            @else
                                                N/A
                                            @endif
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
