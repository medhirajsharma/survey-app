@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add Question to ') }} {{ $survey->title }}</div>

                <div class="card-body">
                    <form action="{{ route('surveys.questions.store', $survey) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="text">Question</label>
                            <input type="text" name="text" id="text" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Options</label>
                            <input type="text" name="options[0][text]" class="form-control mb-2" placeholder="Option 1" required>
                            <input type="text" name="options[1][text]" class="form-control mb-2" placeholder="Option 2" required>
                            <input type="text" name="options[2][text]" class="form-control mb-2" placeholder="Option 3" required>
                            <input type="text" name="options[3][text]" class="form-control mb-2" placeholder="Option 4" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Question</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
