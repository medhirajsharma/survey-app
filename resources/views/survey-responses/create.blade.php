@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $survey->title }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <p>{{ $survey->description }}</p>

                    <form action="{{ route('public.surveys.take', $survey) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="mobile_no">Mobile No</label>
                            <input type="text" name="mobile_no" id="mobile_no" class="form-control" required maxlength="10" pattern="[0-9]{10}" title="Please enter a 10-digit mobile number">
                        </div>

                        <div class="form-group">
                            <label for="vidhansabha_id">Vidhansabha</label>
                            <select name="vidhansabha_id" id="vidhansabha_id" class="form-control" required>
                                <option value="">Select Vidhansabha</option>
                                @foreach ($vidhansabhas as $vidhansabha)
                                    <option value="{{ $vidhansabha->id }}">{{ $vidhansabha->constituency_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="caste">Caste</label>
                            <input type="text" name="caste" id="caste" class="form-control" required>
                        </div>

                        <hr>

                        @foreach ($survey->questions as $key => $question)
                            <div class="mb-4">
                                <h5>{{ $question->text }}</h5>

                                @foreach ($question->options as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="option-{{ $option->id }}" value="{{ $option->id }}" required>
                                        <label class="form-check-label" for="option-{{ $option->id }}">
                                            @if ($option->image_path)
                                                <img src="{{ asset('storage/' . $option->image_path) }}" alt="Option Image" width="30" class="ml-2">
                                            @endif
                                            {{ $option->text }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
