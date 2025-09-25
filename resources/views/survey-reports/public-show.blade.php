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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
