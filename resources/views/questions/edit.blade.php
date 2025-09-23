@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Question') }}</div>

                <div class="card-body">
                    <form action="{{ route('questions.update', $question) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="text">Question</label>
                            <input type="text" name="text" id="text" class="form-control" value="{{ $question->text }}" required>
                        </div>

                        <div class="form-group">
                            <label>Options</label>
                            @for ($i = 0; $i < 4; $i++)
                                @php
                                    $option = $question->options->get($i);
                                @endphp
                                <div class="input-group mb-2">
                                    <input type="hidden" name="options[{{ $i }}][id]" value="{{ $option ? $option->id : '' }}">
                                    <input type="text" name="options[{{ $i }}][text]" class="form-control" placeholder="Option {{ $i + 1 }}{{ $i < 2 ? '' : ' (Optional)' }}" value="{{ $option ? $option->text : '' }}" {{ $i < 2 ? 'required' : '' }}>
                                    <div class="custom-file">
                                        <input type="file" name="options[{{ $i }}][image]" class="custom-file-input" id="option{{ $i }}Image">
                                        <label class="custom-file-label" for="option{{ $i }}Image">Choose file</label>
                                    </div>
                                </div>
                                @if ($option && $option->image_path)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $option->image_path) }}" alt="Option Image" width="100">
                                    </div>
                                @endif
                            @endfor
                        </div>

                        <button type="submit" class="btn btn-primary">Update Question</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.custom-file-input').forEach(function (input) {
        input.addEventListener('change', function (e) {
            var fileName = e.target.files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    });
</script>
@endsection
