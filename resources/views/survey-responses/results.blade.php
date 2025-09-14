@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Survey Results: {{ $survey->title }}</div>

                <div class="card-body">
                    <p><strong>Total Responses:</strong> {{ $totalResponses }}</p>
                    <hr>

                    @foreach ($questionResults as $questionId => $data)
                        <div class="mb-4">
                            <h5>Q: {{ $data['question']->text }}</h5>
                            @foreach ($data['options'] as $optionData)
                                <div class="mb-2">
                                    <p class="mb-1">{{ $optionData['option']->text }} ({{ $optionData['count'] }} votes - {{ $optionData['percentage'] }}%)</p>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $optionData['percentage'] }}%;" aria-valuenow="{{ $optionData['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <hr>
                    <a href="{{ route('public.surveys.take', $survey) }}" class="btn btn-primary">Take Survey Again</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection