@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white"><h4>{{ $survey->title }} - Overall Survey Results</h4></div>

                <div class="card-body">
                    <div class="alert alert-info text-center" role="alert">
                        <strong>Total Responses:</strong> {{ $totalResponses }}
                    </div>

                    <hr class="my-4">

                    @foreach ($questionResults as $questionId => $data)
                        <div class="mb-5">
                            <h5 class="mb-3">Q: {{ $data['question']->text }}</h5>
                            @foreach ($data['options'] as $optionData)
                                <div class="mb-3">
                                    <p class="mb-1">{{ $optionData['option']->text }}</p>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $optionData['percentage'] }}%;" aria-valuenow="{{ $optionData['percentage'] }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ $optionData['percentage'] }}% ({{ $optionData['count'] }} votes)
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <hr class="my-4">
                    <div class="text-center">
                        <a href="{{ route('public.surveys.take', $survey) }}" class="btn btn-success btn-lg">Take Survey Again</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
